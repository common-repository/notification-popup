<?php
/* 
Plugin Name: Notification Popup
Plugin URI: http://wptreasure.com
Description: A very attractive notification popup plugin which gives you a functionality to show your Notification when any user visit your WordPress site. 
Author: wptreasure
Version: 1.0.4
Author URI: http://wptreasure.com
*/

//add style and script in head section
add_action('admin_init','notificationpopup_backend_script');
add_action('wp_enqueue_scripts','notificationpopup_frontend_script');

// backend script
function notificationpopup_backend_script()
{
	if(is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_style('notificationpopup_backend_script',plugins_url('admin/notificationpopup_admin.css',__FILE__));
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'my-script-handle', plugins_url('admin/notificationpopup_admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
}

// frontend script
function notificationpopup_frontend_script()
{
	if(!is_admin())
	{
		wp_enqueue_script('jquery');
	}
}

// Runs when plugin is activated and creates new database field
register_activation_hook(__FILE__,'notificationpopup_plugin_install');

//Add default settings for popup
function notificationpopup_plugin_install() {
    add_option('notificationpopup_options', notificationpopup_defaults());
}

function notificationpopup_defaults()
{
	$options = $_POST;
	    $update_val = array(
		'title' => 'Notification Popup Title',
		'content' => 'Notification Popup Message goes here.',
    	'tcolor' =>'#0c0c0c',
    	'bgcolor' => '#ededed',
		'width' =>'200',
		'height' =>'200',
		'time' =>'5'
    );
	return $update_val;
}

//hook to add admin menu
add_action('admin_menu','notificationpopup_plugin_admin_menu');
function notificationpopup_plugin_admin_menu()
{
	add_menu_page('Notification Popup','Notification Popup','administrator','notificationpopup','notificationpopup_backend_menu',plugins_url('images/popup.png',__FILE__));
}

// update the notificationpopup options
if(isset($_POST['notificationpopup_update']))
{
	update_option('notificationpopup_options', notificationpopup_updates());
}

function notificationpopup_updates()
{
	$options = $_POST;

	if(empty($options['width'])){
		$options['width'] = '400';
	}
	if(empty($options['height'])){
		$options['height'] = '400';
	}
	if(empty($options['time'])){
		$options['time'] = '5';
	}

	$update_val = array(
		'title' => $options['info_title'],
		'content' => $options['infoeditor'],
		'tcolor' =>$options['tcolor'],
		'bgcolor' =>$options['bgcolor'],
		'width' =>$options['width'],
		'height' =>$options['height'],
		'time' =>$options['time']
    );
return $update_val;
}

// Setting Html
function notificationpopup_backend_menu()
{
$options = get_option('notificationpopup_options'); 

?>
<div class="mainwrapper" id="notificationpopup_admin">
		<h2><?php _e('Notification Popup '.notificationpopup_get_version().' Setting\'s','notificationpopup'); ?></h2>
		<div>
			<form method="post">
				<table class="ptable">
					<tr>
						<td><?php _e('Popup Title','notificationpopup'); ?></td>
						<td><input type="text" name="info_title" class="title_text" value="<?php if($options['title']) echo $options['title']; ?>" placeholder="Enter Title"/></td>
					</tr>
					<tr>
						<td><?php _e('Popup Content','notificationpopup'); ?></td>
						<td>
							<?php
								
								if(!empty($options['content']))
								{
									$content = $options['content'];
								}
								else
								{
									$content = '';
								}
								$editor_id = 'infoeditor';
								wp_editor( stripslashes( $content ), $editor_id );
							?>
						</td>
					</tr>
					<tr>
						<td><?php _e('Text Color','notificationpopup'); ?></td>
						<td>
							<div class="color-picker" style="position: relative;">
								<input type="text" value="<?php if($options['tcolor']) echo $options['tcolor']; else echo "#0F67A1"; ?>" class="color" name="tcolor" />
							</div>
						</td>
					</tr>
					<tr>
						<td><?php _e('Background Color','notificationpopup'); ?></td>
						<td>
							<div class="notificationpopup_colwrap">
								<input type="text" value="<?php if($options['bgcolor']) echo $options['bgcolor']; else echo "#0F67A1"; ?>" class="color" name="bgcolor" />
							</div>
						</td>
					</tr>
					<tr>
						<td><?php _e('Width','notificationpopup'); ?></td>
						<td>
							<div class="notificationpopup_colwrap">
								<input type="text" value="<?php if($options['width']) echo $options['width']; else echo "400"; ?>" name="width" />&nbsp;<span><small><?php _e('in Pixel','notificationpopup'); ?></small></span>
							</div>
						</td>
					</tr>
					<tr>
						<td><?php _e('Height','notificationpopup'); ?></td>
						<td>
							<div class="notificationpopup_colwrap">
								<input type="text" value="<?php if($options['height']) echo $options['height']; else echo "400"; ?>" name="height" />&nbsp;<span><small><?php _e('in Pixel','notificationpopup'); ?></small></span>
							</div>
						</td>
					</tr>
					<tr>
						<td><?php _e('Time Interval','notificationpopup'); ?></td>
						<td>
							<div class="notificationpopup_colwrap">
								<input type="text" value="<?php if($options['time']) echo $options['time']; else echo "5"; ?>" name="time" />&nbsp;<span><small><?php _e('in Seconds','notificationpopup'); ?></small></span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" value="<?php _e('Save Settings','notificationpopup'); ?>" class="button-primary" id="notificationpopup_update" name="notificationpopup_update">	
						</td>
					</tr>
				</table>
			</form>
		</div>
</div>
<?php
}
	
// get notificationpopup version
function notificationpopup_get_version(){
	if ( ! function_exists( 'get_plugins' ) )
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}

//Add meta Box
add_action('admin_init','notificationpopup_meta_box');
function notificationpopup_meta_box(){
	add_meta_box('notificationpopup_page', 'Notification Popup', 'notificationpopup_status_meta_box', 'page', 'side','high');
}

function notificationpopup_status_meta_box($page) {
	$meta_values = get_post_meta( $page->ID, '_notificationpopup_status');
	
	if(!empty($meta_values[0]))
	{
		$status = 'selected=selected';
		$status_d = '';
	}
	else
	{
		$status_d = 'selected=selected';
		$status = '';
	}
	
	echo '<select name="_notificationpopup_status"><option value="1" '.$status.'>Enable</option><option value="0" '.$status_d.'>Disable</option></select>';
}

// Save Meta Box
add_action( 'save_post', 'notificationpopup_status_meta_box_save' );
function notificationpopup_status_meta_box_save()
{
	$post_id = isset($_REQUEST['post_ID'])?$_REQUEST['post_ID']:'';
	// Verify this came from the our screen and with proper authorization,
      // because save_post can be triggered at other times
	  $nonce = isset($_REQUEST['_wpnonce'])?$_REQUEST['_wpnonce']:'';
      /*if (!wp_verify_nonce($nonce)) {
        return $post_id;
      }*/
	 
	  // Verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
      // to do anything
      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;
		
	  // Check permissions to edit pages and/or posts
      if ( isset($_POST['post_type']) && ('page' == $_POST['post_type'] ||  'post' == $_POST['post_type'])) {
        if ( !current_user_can( 'edit_page', $post_id ) || !current_user_can( 'edit_post', $post_id ))
          return $post_id;
      } 
	
	  //we're authenticated: we need to find and save the data
      $status = isset($_POST['_notificationpopup_status'])?$_POST['_notificationpopup_status']:'';
      // save data in INVISIBLE custom field (note the "_" prefixing the custom fields' name
      update_post_meta($post_id, '_notificationpopup_status', $status); 
}


//Create a popup on theme
function notificationpopup()
{
	$options = get_option('notificationpopup_options'); 
	global $wp_query;
	$postid = $wp_query->post->ID;
	$popup_status = get_post_meta($postid, '_notificationpopup_status', true);	
	$popup_status = (isset($popup_status) && $popup_status ) ? $popup_status : '0';
	if(( is_page() && $popup_status )  || is_front_page())
	{
	?>
	<!-- Notification Popup Starts Here -->
	<style type="text/css">
	#notificationpopup{
		background: none repeat scroll 0 0 <?php echo $options['bgcolor']; ?>;
		color: <?php echo $options['tcolor']; ?>;
		border: 15px solid #111111;
		font-family: arial;
		font-size: 14px;
		line-height: 25px;
		margin: 0 auto;
		overflow: visible;
		padding: 5px;
		position: absolute;
		top: 50px;
		width: <?php echo $options['width']; ?>px;
		display:none;
		left:1%;
		right:1%;
		height:<?php echo $options['height']; ?>px
	}

	#notificationpopup a.notp_closepopup {
		cursor: pointer;
		position: absolute;
		right: -34px;
		top: -38px;
	}

	#notificationpopup .notp_wrap {
		-moz-box-sizing: border-box;
		height: 100%;
		overflow-y: scroll;
		padding: 10px;
		box-sizing: border-box;
		line-height: 20px;
	}

	#notificationpopup_box {
		background: url("<?php echo plugins_url( 'images/bg.png' , __FILE__ ) ?>") repeat scroll 0 0 rgba(0, 0, 0, 0);
		display: none;
		height: 100%;
		left: 0;
		position: fixed;
		top: 0;
		width: 100%;
		z-index: 999999;
	}

	#notificationpopup .notp_title{
		text-align:center; 
		line-height: 24px;
	}
	</style>
	
	<div style="display: none;" id="notificationpopup_box">		
		<div id="notificationpopup">
			<a class="notp_closepopup">
			<?php
				echo '<img src="' . plugins_url( 'images/close.png' , __FILE__ ) . '" >';
			?>
			</a>
			<div class="notp_wrap">
				<h1 class="notp_title"><?php echo $options['title']; ?></h1>
				<br>
				<?php echo do_shortcode( stripslashes( $options['content'] ) ); ?>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			setTimeout(function(){notificationpopup_display_popup()},<?php echo $options['time']*1000; ?>);
		});
		
		function notificationpopup_display_popup(){
			jQuery('#notificationpopup').fadeIn(100);
			jQuery('#notificationpopup_box').fadeIn(100);
		}
		
		function notificationpopup_close_popup(){
			jQuery('#notificationpopup_box').fadeOut(100);
			jQuery('#notificationpopup').fadeOut(100);
		}

		jQuery(document).ready(function() {
			jQuery(document).keyup(function(e) { 
				if (e.keyCode == 27) { 
					notificationpopup_close_popup();
				}
			});
			jQuery('a.notp_closepopup').click(function(e) { 
				notificationpopup_close_popup();        
			});
		});
	</script>
	<!-- Notification Popup Ends Here -->
	<?php
	}
}
add_action('wp_footer', 'notificationpopup');
?>