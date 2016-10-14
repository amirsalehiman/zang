<?php

	if(isset($_POST['action'])){
		$success = $failed = 0;
		foreach($_POST['text_delete_id'] as $delete_id){
			$delete = $wpdb->delete($wpdb->prefix . "woocommerce_ir",array('id' => intval($delete_id)));
			if($delete) 
				$success++;
			else
				$failed++;				
		}
		if($success)
			add_settings_error("delete_text", "persian_wc_msg", sprintf("%d حلقه با موفقیت حذف شدند.", $success), "updated");
		if($failed)
			add_settings_error("delete_text", "persian_wc_msg", sprintf("حذف %d حلقه با شکست مواجه شد.", $failed));
	}

	add_action("admin_print_footer_scripts","persian_wc_text_footer");

	function persian_wc_text_footer() {
		?>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {
			$("#persian_wc_text").submit(function(){
				$("#save_loop_button").val("در حال ذخیره ...");
				jQuery.post(ajaxurl, $("#persian_wc_text").serialize() , function(response) {
					
					var obj = jQuery.parseJSON(response);
					
					if(obj.status == "OK"){
						$("#the-list").prepend(obj.code);
						$(".displaying-num").html(obj.count);
						document.getElementById("persian_wc_text").reset();
					
						if(obj.count == "1 مورد")
							$("tr.no-items").remove();
					}
					setTimeout(function () { $("#setting-error-persian_wc_msg_"+ obj.rand).slideUp('slow' , function(){ $("#setting-error-persian_wc_msg_"+ obj.rand).remove(); }) }, 3000);
					$(".wrap h2#title").after(obj.msg);
				});
				
				setTimeout(function () { $("#save_loop_button").val("ذخیره حلقه"); }, 2000);
				
				return false;
			});
		});
		</script>
		<?php
	}

	add_action( 'add_meta_boxes', 'woocommerce_text_add_meta_box' );

	function woocommerce_text_add_meta_box(){
		add_meta_box('add_form','افزودن حلقه ترجمه','woocommerce_text_meta_box','persian_wc_text','side','high');
	}	

	function woocommerce_text_meta_box(){
		?>
		<form action="" method="post" id="persian_wc_text">
			<input type="hidden" name="action" value="persian_wc_replace_texts" />
			<label for="input_text_1">کلمه‌ی مورد نظر :</label>
			<input type="text" class="widefat" size="30" id="input_text_1" name="text1" />
			<br>
			<label for="input_text_2">جایگزین شود با :</label>
			<input type="text" class="widefat" size="30" id="input_text_2" name="text2" />
			</div>
			<div id="major-publishing-actions">
				<div id="publishing-action">
					<span class="spinner"></span>
					<?php submit_button( esc_attr( 'ذخیره حلقه' ), 'primary', 'submit', false, array('id' => 'save_loop_button')); ?>
				</div>
				<div class="clear"></div>
			
		</form>
		<?php
	}

	if( ! class_exists( 'WP_List_Table' ) )
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

	class persian_wc_text_list extends WP_List_Table {

		var $data = array();
		function __construct(){
			global $status, $page, $wpdb;
			
			$perPage = 8;
			$currentPage = $this->get_pagenum();
			$db_page = ($currentPage - 1) * $perPage;
			$this->data = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}woocommerce_ir` ORDER BY id DESC LIMIT $db_page,$perPage;", ARRAY_A);
			
			$totalItems = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}woocommerce_ir`;");
			$this->set_pagination_args( array(
				'total_items' => $totalItems,
				'per_page'    => $perPage,
				'total_pages'   => ceil( $totalItems / $perPage )
			) );

			parent::__construct( array(
				'singular'  => 'text',
				'plural'    => 'texts',
				'ajax'      => false
			) );
		}

		function column_default( $item, $column_name ) {
			switch( $column_name ) { 
				case 'cb':
				case 'text1':
				case 'text2':
					return $item[$column_name];
				default:
					return print_r( $item, true ) ;
			}
		}

		function get_columns(){
				$columns = array(
					'cb' => '<input type="checkbox" />',
					'text1' => 'کلمه اصلی',
					'text2' => 'کلمه جایگزین شده',
				);
				return $columns;
		}
		
		function column_cb($item) {
			return sprintf('<input type="checkbox" name="text_delete_id[]" value="%s" />', $item['id']);    
		}
		
		function prepare_items() {
			$columns  = $this->get_columns();
			$hidden   = array();
			$sortable = array();
			$this->_column_headers = array( $columns, $hidden, $sortable );
			$this->items = $this->data;;
		}
		
		function get_bulk_actions() {
			$actions = array('delete' => 'حذف');
			return $actions;
		}
		
	}
	
	$persian_wc_text_list = new persian_wc_text_list();
	do_action('add_meta_boxes', 'persian_wc_text');
	
	?>
	<div class="wrap">

		<h2 id="title">حلقه های ترجمه</h2>


		<div class="fx-settings-meta-box-wrap">
				
				<div id="poststuff">

					<div id="post-body" class="metabox-holder columns-2">

						<div id="postbox-container-1" class="postbox-container">
							
							<?php do_meta_boxes('persian_wc_text', 'side', null); ?>
							<!-- #side-sortables -->

						</div><!-- #postbox-container-1 -->

						<div id="postbox-container-2" class="postbox-container">
							
							<?php settings_errors(); 
						
								echo '<form method="POST" id="list-project">';
								$persian_wc_text_list->prepare_items(); 
								$persian_wc_text_list->display(); 
								echo '</form>';							
								
							?>
							
							<div class="clear"></div>
							<!-- #normal-sortables -->

						</div><!-- #postbox-container-2 -->

					</div><!-- #post-body -->

					<br class="clear">

				</div><!-- #poststuff -->

		</div><!-- .fx-settings-meta-box-wrap -->

	</div><!-- .wrap -->