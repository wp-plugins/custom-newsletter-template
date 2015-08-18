<?php
/*
Plugin Name: Custom Newsletter Template
Plugin URI: http://www.cybernetikz.com
Description: Custom Template Generator for Newsletter
Version: 1.0
Author: cybernetikz
Author URI: http://www.cybernetikz.com
License: GPL2
*/

$pluginsURI = plugins_url('/custom-newsletter-template/');
$msg = "";

add_filter( 'page_template', 'cn_cnt_custom_page_template' );
function cn_cnt_custom_page_template( $page_template )
{
	$page_id = get_option('cnt-template-page-id');
	if($page_id) {
		$pageObj = get_page( $page_id );
		$slug = $pageObj->post_name;
		if ( is_page( $slug ) and $slug !='' ) {
			$page_template = dirname( __FILE__ ) . '/custom-page-template.php';
			return $page_template;
		}
	}
}

function cnt_db_install () {
	add_option('cnt-header-img','');
	
	add_option('cnt-ads1-img','');
	add_option('cnt-ads1-link','');
	
	add_option('cnt-ads2-img','');
	add_option('cnt-ads2-link','');
	
	add_option('cnt-social-img','');
	add_option('cnt-social-link','');
	
	add_option('cnt-todays-featured-title', 'Featured post title');
	add_option('cnt-featured-articles-title', 'Featured articles title');
		
	add_option('cnt-footer-content', '<p>Footer content here</p>');
	add_option('cnt-footer-bgcolor', '#ebebeb');
		
	add_option('cnt-link-color', '#999999');
	add_option('cnt-font-color', '#000000');
	add_option('cnt-font-color-date', '#666666');
	
	add_option('cnt-read-more-text', 'Read More');

	add_option('cnt-background-repeat', 'repeat');
	add_option('cnt-background-color', '#ffffff');
	
	add_option('cnt-favicon', '');
	add_option('cnt-page-title','Page Title');

	add_option('cnt-featured-articles-post-count', 5);
	add_option('cnt-sidebar-post-count', 6);
	
	$cnt_page = array(
	  'post_title'    => 'Custom Newsletter Template',
	  'post_content'  => '',
	  'post_type'     => 'page',
	  'post_status'   => 'publish',
	  'post_author'   => 1
	);
	$template_page_id = wp_insert_post( $cnt_page );
	if($template_page_id)
		add_option('cnt-template-page-id', $template_page_id);
}

function cnt_db_uninstall () {
	$page_id = get_option('cnt-template-page-id');
	wp_delete_post($page_id, true);
	delete_option( 'cnt-template-page-id' );
}

register_activation_hook(__FILE__,'cnt_db_install');
register_deactivation_hook( __FILE__, 'cnt_db_uninstall' );

function update_opt($field)
{
	if(isset($_POST[$field]))
	{
		update_option($field, stripslashes($_POST[$field]) );
	}
}

if(isset($_POST['update_content']) and $_POST['update_content']=='yes'){

	//print_r($_FILES);
	
	$fileArray = array(
	'0'=>'cnt-header-img',
	'1'=>'cnt-ads1-img',
	'2'=>'cnt-ads2-img',
	'3'=>'cnt-social-img',
	'4'=>'cnt-bg-img',
	'5'=>'cnt-sidebar-img',
	'6'=>'cnt-favicon',
	);
	
	
	if(isset($_POST['cnt-social-link']))
	{
		update_option('cnt-social-link',$_POST['cnt-social-link']);
	}
	if(isset($_POST['cnt-ads1-link']))
	{
		update_option('cnt-ads1-link',$_POST['cnt-ads1-link']);
	}
	if(isset($_POST['cnt-ads2-link']))
	{
		update_option('cnt-ads2-link',$_POST['cnt-ads2-link']);
	}
	
	if(isset($_POST['cnt-todays-featured-title']))
	{
		
		update_option('cnt-todays-featured-title', stripslashes($_POST['cnt-todays-featured-title']) );
	}
	
	if(isset($_POST['cnt-featured-articles-title']))
	{
		update_option('cnt-featured-articles-title', stripslashes($_POST['cnt-featured-articles-title']) );
	}

	update_opt('cnt-footer-content');
	update_opt('cnt-footer-bgcolor');
	update_opt('cnt-link-color');
	update_opt('cnt-font-color');
	update_opt('cnt-font-color-date');
	update_opt('cnt-read-more-text');
	update_opt('cnt-background-repeat');
	update_opt('cnt-background-color');
	update_opt('cnt-favicon');
	update_opt('cnt-page-title');
	update_opt('cnt-featured-articles-post-count');
	update_opt('cnt-sidebar-post-count');


	foreach($_FILES['image_file']['name'] as $key=>$filename)
	{
		
		$image_file_path = "../wp-content/uploads/";
		$imgExtArray = array('image/gif','image/jpeg','image/pjpeg','image/png','image/x-icon');
		
		if ($filename != "" and $_FILES["image_file"]["error"][$key]==0) {
		
			if( in_array($_FILES["image_file"]["type"][$key],$imgExtArray) 
				&& $_FILES["image_file"]["size"][$key] <= 1024*1024*1 )
			{
				
				if (file_exists($image_file_path . $_FILES["image_file"]["name"][$key]))
				  {
				  $msg .= $_FILES["image_file"]["name"][$key] . " already exists"."<br />";
				  }
				else
				  {
					$image_file_name = time().'_'.$_FILES["image_file"]["name"][$key];
					$fstatus = move_uploaded_file($_FILES["image_file"]["tmp_name"][$key], $image_file_path . $image_file_name);
					
					if ($fstatus == true) {
						$oldFileName = get_option($fileArray[$key]);
						update_option($fileArray[$key],$image_file_name);
						$msg .= "File upload successful"."<br />";
						@unlink($image_file_path.$oldFileName);
					}
				  }
				
			}
			else
			{
				$msg .= $_FILES["image_file"]["type"][$key]." Invalid file type or max file size exceded"."<br />";
			}
		}
		/*else
		{
			$msg .= "Error on file upload"."<br />";
		}*/
	}
}

/*function cnt_css() {
	echo "
	<style type='text/css'>
	table.form-table tr {
	border-bottom:#CCCCCC 1px dotted;
	}
	</style>
	";
}
if($_GET['page']=='cnt_page')
	add_action( 'admin_head', 'cnt_css' );*/

add_action('admin_menu', 'cnt_add_menu_pages');

function cnt_add_menu_pages() {
	add_menu_page('Newsletter Template', 'Custom Template', 'manage_options', 'cnt_page', 'cnt_page_fn', plugins_url('/images/nl.png', __FILE__) );
	//add_action( 'admin_init', 'register_cnt_settings' );
}

/*function register_cnt_settings() {
	register_setting( 'cnt-settings-group', 'cnt-header-img' );

	register_setting( 'cnt-settings-group', 'cnt-ads1-img' );
	register_setting( 'cnt-settings-group', 'cnt-ads1-link' );
	
	register_setting( 'cnt-settings-group', 'cnt-ads2-img' );
	register_setting( 'cnt-settings-group', 'cnt-ads2-link' );
	
	register_setting( 'cnt-settings-group', 'cnt-social-img' );
	register_setting( 'cnt-settings-group', 'cnt-social-link' );
}*/

function cnt_page_fn() {

	global $msg;
	$image_file_path = "../wp-content/uploads/";
	
	$cnt_header_img = get_option('cnt-header-img');
	$cnt_ads1_img = get_option('cnt-ads1-img');
	$cnt_ads1_link = get_option('cnt-ads1-link');

	$cnt_ads2_img = get_option('cnt-ads2-img');
	$cnt_ads2_link = get_option('cnt-ads2-link');
	
	$cnt_social_img = get_option('cnt-social-img');
	$cnt_social_link = get_option('cnt-social-link');
	
	$cnt_bg_img = get_option('cnt-bg-img');
	$cnt_sidebar_img = get_option('cnt-sidebar-img');
	
	$cnt_todays_featured_title = get_option('cnt-todays-featured-title');
	$cnt_featured_articles_title = get_option('cnt-featured-articles-title');
	
	$cnt_footer_content = get_option('cnt-footer-content');
	$cnt_footer_bgcolor = get_option('cnt-footer-bgcolor');
	
	$cnt_link_color = get_option('cnt-link-color');
	$cnt_font_color = get_option('cnt-font-color');
	$cnt_font_color_date = get_option('cnt-font-color-date');
	
	$cnt_read_more_text = get_option('cnt-read-more-text');
	
	$cnt_background_repeat = get_option('cnt-background-repeat');
	$cnt_background_color = get_option('cnt-background-color');
	
	$cnt_favicon = get_option('cnt-favicon');
	$cnt_page_title = get_option('cnt-page-title');
	
	$cnt_featured_articles_post_count = get_option('cnt-featured-articles-post-count');
	$cnt_sidebar_post_count = get_option('cnt-sidebar-post-count');
	
	?>
	<div class="wrap">
	<?php if($msg!='') echo '<div id="message" class="updated fade">'.$msg.'</div>'; ?>
	<div class="icon32" id="icon-options-general"><br></div>
	<h2>Custom Newsletter Template</h2>
	<form method="post" action="" enctype="multipart/form-data">
		<?php //settings_fields( 'cnt-settings-group' ); ?>
		<table class="form-table">
		
			<tr valign="top">
			<th scope="row">Page Title</th>
			<td><input type="text" name="cnt-page-title" id="cnt-page-title" class="regular-text" value="<?php echo $cnt_page_title ?>" /></td>
			</tr>
		
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Header image</th>
			<td><?php if ($cnt_header_img!='') { ?><img src="<?php echo $image_file_path.$cnt_header_img?>" border="0" width="" /><br /><?php } ?><input type="file" name="image_file[]" id="image_file0" value="" /><em>783 x 112px</em></td>
			</tr>
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Ads 1 image</th>
			<td><?php if ($cnt_ads1_img!='') { ?><img src="<?php echo $image_file_path.$cnt_ads1_img?>" border="0" width="" /><br /><?php } ?>
				<input type="file" name="image_file[]" id="image_file1" value="" /><em>728 x 90px</em></td>
			</tr>
			
			<th scope="row">Ads 1 link</th>
			<td>
				<input type="text" name="cnt-ads1-link" id="cnt-ads1-link" class="regular-text" value="<?php echo $cnt_ads1_link?>" /><br /><i>Example: <strong>http://domain.com/page</strong> &ndash; don't forget the <strong><code>http://</code></strong></i></td></td>
			</tr>
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Ads 2 image</th>
			<td><?php if ($cnt_ads2_img!='') { ?><img src="<?php echo $image_file_path.$cnt_ads2_img?>" border="0" width="" /><br /><?php } ?>
				<input type="file" name="image_file[]" id="image_file2" value="" /><em>300 x 250px</em></td>
			</tr>
			
			<th scope="row">Ads 2 link</th>
			<td>
				<input type="text" name="cnt-ads2-link" id="cnt-ads2-link" class="regular-text" value="<?php echo $cnt_ads2_link?>" /><br /><i>Example: <strong>http://domain.com/page</strong> &ndash; don't forget the <strong><code>http://</code></strong></i></td></td>
			</tr>

			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Social image</th>
			<td><?php if ($cnt_social_img!='') { ?><img src="<?php echo $image_file_path.$cnt_social_img?>" border="0" width="" /><br /><?php } ?>
			<input type="file" name="image_file[]" id="image_file3" value="" /><em>300 x 70px</em></td>
			</tr>
			
			<tr valign="top">
			<th scope="row">Social link</th>
			<td>
				<input type="text" name="cnt-social-link" id="cnt-social-link" class="regular-text" value="<?php echo $cnt_social_link?>" /><br /><i>Example: <strong>http://domain.com/page</strong> &ndash; don't forget the <strong><code>http://</code></strong></i></td>
			</tr>
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Background image</th>
			<td><?php if ($cnt_bg_img!='') { ?><img src="<?php echo $image_file_path.$cnt_bg_img?>" border="0" width="" /><br /><?php } ?>
			<input type="file" name="image_file[]" id="image_file4" value="" /></td>
			</tr>
			
			<tr valign="top">
			<th scope="row">Background color</th>
			<td><input style="width:70px;" type="text" name="cnt-background-color" id="cnt-background-color" class="small-text" value="<?php echo $cnt_background_color?>" /> <em>Example: #ffffff</em>&nbsp;&nbsp;<span style="background-color:<?php echo $cnt_background_color?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
			</tr>
			
			
			<tr valign="top">
			<th scope="row">Background repeat</th>
			<td>
				<select name="cnt-background-repeat" class="regular-text">
					<option <?php if($cnt_background_repeat=='repeat') echo 'selected="selected"' ?> value="repeat">repeat</option>
					<option <?php if($cnt_background_repeat=='no-repeat') echo 'selected="selected"' ?> value="no-repeat">no-repeat</option>
					<option <?php if($cnt_background_repeat=='repeat-x') echo 'selected="selected"' ?> value="repeat-x">repeat-x</option>
					<option <?php if($cnt_background_repeat=='repeat-y') echo 'selected="selected"' ?> value="repeat-y">repeat-y</option>
				</select></td>
			</tr>
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Sidebar title image</th>
			<td><?php if ($cnt_sidebar_img!='') { ?><img src="<?php echo $image_file_path.$cnt_sidebar_img?>" border="0" width="" /><br /><?php } ?>
			<input type="file" name="image_file[]" id="image_file5" value="" /> <em>300 x 37px</em></td>
			</tr>
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Featured post title</th>
			<td><div id="poststuff"><?php 
			$settings = array('media_buttons'=>false,'wpautop'=>false);
			wp_editor($cnt_todays_featured_title, 'cnt-todays-featured-title',$settings); ?></div></td>
			</tr>
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Featured articles title</th>
			<td><div id="poststuff"><?php 
			//$settings = array('media_buttons'=>false);
			wp_editor($cnt_featured_articles_title, 'cnt-featured-articles-title',$settings); ?></div></td>
			</tr>

			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Footer content</th>
			<td><div id="poststuff"><?php 
			//$settings = array('media_buttons'=>false);
			wp_editor($cnt_footer_content, 'cnt-footer-content',$settings); ?></div></td>
			</tr>
			
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Footer background color</th>
			<td><input style="width:70px;" type="text" name="cnt-footer-bgcolor" id="cnt-footer-bgcolor" class="small-text" value="<?php echo $cnt_footer_bgcolor?>" /> <em>Example: #fff000</em>&nbsp;&nbsp;<span style="background-color:<?php echo $cnt_footer_bgcolor?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
			</tr>

			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Link color</th>
			<td><input style="width:70px;" type="text" name="cnt-link-color" id="cnt-link-color" class="small-text" value="<?php echo $cnt_link_color?>" /> <em>Example: #00ff00</em>&nbsp;&nbsp;<span style="background-color:<?php echo $cnt_link_color?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
			</tr>

			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Font color</th>
			<td><input style="width:70px;" type="text" name="cnt-font-color" id="cnt-font-color" class="small-text" value="<?php echo $cnt_font_color?>" /> <em>Example: #333333</em>&nbsp;&nbsp;<span style="background-color:<?php echo $cnt_font_color?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
			</tr>
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Font color <em>for post date</em></th>
			<td><input style="width:70px;" type="text" name="cnt-font-color-date" id="cnt-font-color-date" class="small-text" value="<?php echo $cnt_font_color_date?>" /> <em>Example: #333333</em>&nbsp;&nbsp;<span style="background-color:<?php echo $cnt_font_color_date?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
			</tr>
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Read more text</th>
			<td>
				<input type="text" name="cnt-read-more-text" id="cnt-read-more-text" class="regular-text" value="<?php echo $cnt_read_more_text?>" /><em>Example: Read more... or Continue...</em></td></td>
			</tr>
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Favicon</th>
			<td><?php if ($cnt_favicon!='') { ?><img src="<?php echo $image_file_path.$cnt_favicon?>" border="0" width="" /><br /><?php } ?>
			<input type="file" name="image_file[]" id="image_file6" value="" /> <em>ico ext only</em></td>
			</tr>
			
			<tr style="border-top:#CCCCCC 1px dotted;" valign="top">
			<th scope="row">Featured articles post count</th>
			<td>
				<input type="text" name="cnt-featured-articles-post-count" id="cnt-featured-articles-post-count" class="small-text" value="<?php echo $cnt_featured_articles_post_count?>" /> <em>Example: 5</em></td></td>
			</tr>

			<tr valign="top">
			<th scope="row">Featured articles post count <br /><em>for Sidebar</em></th>
			<td>
				<input type="text" name="cnt-sidebar-post-count" id="cnt-sidebar-post-count" class="small-text" value="<?php echo $cnt_sidebar_post_count?>" /> <em>Example: 6</em></td></td>
			</tr>
			
		</table>
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
		<input type="hidden" name="update_content" value="yes" />
	</form>
	</div>
<?php
}
?>