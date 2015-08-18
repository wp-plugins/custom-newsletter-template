<?php
function cnt_chop_str($str, $len)
{
	if (strlen($str) > $len)
	{
		$split_title = str_split($str,$len);
		$output = $split_title[0].'...';
	}
	else
		$output = $str;
	return $output;
}
$image_file_path = get_option('siteurl')."/wp-content/uploads/";

$cnt_header_img = $image_file_path.get_option('cnt-header-img');
$cnt_ads1_img = $image_file_path.get_option('cnt-ads1-img');
$cnt_ads1_link = get_option('cnt-ads1-link');

$cnt_ads2_img = $image_file_path.get_option('cnt-ads2-img');
$cnt_ads2_link = get_option('cnt-ads2-link');

$cnt_social_img = $image_file_path.get_option('cnt-social-img');
$cnt_social_link = get_option('cnt-social-link');

$cnt_bg_img = $image_file_path.get_option('cnt-bg-img');
$cnt_sidebar_img = $image_file_path.get_option('cnt-sidebar-img');

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

$cnt_favicon = $image_file_path.get_option('cnt-favicon');
$cnt_page_title = get_option('cnt-page-title');

$cnt_featured_articles_post_count = get_option('cnt-featured-articles-post-count');
$cnt_sidebar_post_count = get_option('cnt-sidebar-post-count');
?>
<html>
<head>
<link rel="shortcut icon" href="<?php echo $cnt_favicon ?>">
<title><?php echo $cnt_page_title ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="background:url(<?php echo $cnt_bg_img ?>) top center; height:100%;background-color:<?php echo $cnt_background_color ?>;font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:11px;color:#000; background-repeat:<?php echo $cnt_background_repeat ?>;">
<table width="810" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" background="<?php echo $pluginsURI ?>images/body-bg.png"><table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div align="center"><img src="<?php echo $cnt_header_img ?>" width="783" height="112"></div></td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div align="center" style="padding-bottom:15px;"><a target="_blank" href="<?php echo $cnt_ads1_link ?>"><img src="<?php echo $cnt_ads1_img ?>" width="728" height="90"></a></div></td>
      </tr>
      <tr>
        <td valign="top"><div align="center"><img src="<?php echo $pluginsURI ?>images/hr-big.jpg" width="783" height="7"></div></td>
      </tr>
      <tr>
        <td valign="top"><table width="783" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="470" valign="top"><table width="470" border="0" align="left" cellpadding="0" cellspacing="0">
                    <tr>
                <td valign="top" style="font-family: verdana;font-size: 17px; color: #000;" class="purple-header"><?php echo $cnt_todays_featured_title ?></td>
              </tr>
              <tr>
                <td valign="top"><table width="470" border="0" align="left" cellpadding="0" cellspacing="0">
                  <tr>
					<?php $query = new WP_Query( array( 'post_type' => 'post', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => 1 ) ); ?>
					<?php $i=1; while ($query->have_posts()) : $query->the_post(); ?>
					<?php $url = get_permalink() ?>
                    <td width="237" valign="top"><?php if ( has_post_thumbnail()) { $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'); ?><img src="<?php echo $image_url[0] ?>" width="224" height="225" class="image-border" style="border: #CCCCCC 1px solid; text-align:left;" ><?php } ?></td>
                    <td width="233" valign="top" style="font-family: verdana;font-size: 11px;line-height:17px;font-weight:normal;text-align:left;color: <?php echo $cnt_font_color ?>;"><span class="purple" style="font-family: verdana;font-size: 11px;line-height:17px;	font-weight:normal;	text-align:left;color:<?php echo $cnt_font_color_date ?>;"><?php the_time('l, F j, Y') ?></span><br>
                        <span style="font-family: verdana;font-size: 15px;line-height:17px;font-weight:normal; text-align:left;color: <?php echo $cnt_font_color ?>;"><strong><?php the_title(); ?></strong></span><br><?php the_excerpt(); ?><a style="color: <?php echo $cnt_link_color ?>;font-weight: bold;" href="<?php echo $url ?>"><?php echo $cnt_read_more_text?></a></td>
					  <?php $i++; endwhile; wp_reset_query(); ?>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td valign="top"><img src="<?php echo $pluginsURI ?>images/hr-side.jpg" width="470" height="13"></td>
              </tr>
              <tr>
                      <td valign="bottom" style="font-family:verdana; font-size:15px; color:#60003F; text-align:left;" class="purple-header"><?php echo $cnt_featured_articles_title ?> 

                        <img src="<?php echo $pluginsURI ?>images/hr-side.jpg" width="470" height="13"></td>
              </tr>
			<?php $query = new WP_Query( array( 'post_type' => 'post', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => $cnt_featured_articles_post_count, 'offset' => 1 ) ); ?>
			<?php $i=1; while ($query->have_posts()) : $query->the_post(); ?>
			<?php $url = get_permalink() ?>
              <tr>
                <td valign="top">
				<table width="470" border="0" align="left" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="72" valign="top"><?php if ( has_post_thumbnail()) { $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail'); ?><img src="<?php echo $image_url[0] ?>" width="60" height="60" class="image-border" style="border: #CCCCCC 1px solid; text-align:left;" ><?php } ?></td>
                    <td width="398" valign="top" style="font-family: verdana;font-size: 11px;line-height:17px;font-weight:normal;text-align:left;color: <?php echo $cnt_font_color ?>;"><strong><?php the_title(); ?></strong><br><?php echo cnt_chop_str(get_the_excerpt(),132); ?><br><a style="color:<?php echo $cnt_link_color ?>;font-weight: bold;" href="<?php echo $url ?>"><?php echo $cnt_read_more_text?></a>
                    </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td valign="top"><img src="<?php echo $pluginsURI ?>images/hr-side.jpg" width="470" height="13"></td>
              </tr>
			  <?php $i++; endwhile; ?>
              <tr>
                <td valign="top">&nbsp;</td>
              </tr>
            </table></td>
            <td width="13">&nbsp;</td>
            <td width="300" valign="top"><div align="right">
              <table width="300" border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <td valign="top"><a target="_blank" href="<?php echo $cnt_social_link ?>"><img src="<?php echo $cnt_social_img ?>" width="300" height="70"></a></td>
                </tr>
                <tr>
                  <td valign="top"><img src="<?php echo $pluginsURI ?>images/hr.jpg" width="300" height="7"></td>
                </tr>
                <tr>
                  <td valign="top"><a target="_blank" href="<?php echo $cnt_ads2_link ?>"><img src="<?php echo $cnt_ads2_img ?>" width="300" height="250"></a></td>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top"><img src="<?php echo $cnt_sidebar_img ?>" width="300" height="37"></td>
                </tr>
                <tr>
                  <td valign="top" style="font-family: verdana;font-size: 11px;line-height:17px;font-weight:normal;text-align:left;color: <?php echo $cnt_font_color ?>;"><p><br>
					<?php $query = new WP_Query( array( 'post_type' => 'post', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => $cnt_sidebar_post_count, 'offset' => $cnt_featured_articles_post_count+1 ) ); ?>
					<?php $i=1; while ($query->have_posts()) : $query->the_post(); ?>
					<a style="color:<?php echo $cnt_link_color ?>;font-weight: bold;" href="<?php the_permalink() ?>"><?php echo cnt_chop_str(get_the_title(),48); ?></a>
					<br>
                    <?php echo cnt_chop_str(get_the_excerpt(),48); ?><br>
                    <img src="<?php echo $pluginsURI ?>images/hr.jpg" width="300" height="7">
					<?php $i++; endwhile; ?>
                    <p></p></td>
                </tr>
              </table>
            </div></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top" background="<?php echo $pluginsURI ?>images/body-bg.png">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" background="<?php echo $pluginsURI ?>images/body-bg.png"><table width="783" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="47" valign="middle" bgcolor="<?php echo $cnt_footer_bgcolor ?>"><div align="center" class="footer-text" style="font-family:verdana; font-size:11px; line-height:17px; font-weight:normal; color:#000;"><?php echo $cnt_footer_content ?></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><img src="<?php echo $pluginsURI ?>images/body-footer.png" width="810" height="13"></td>
  </tr>
</table>
</body>
</html>