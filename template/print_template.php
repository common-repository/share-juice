<?php


//first sanitize
//sanitize id for only numbers thank you PHP
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
//santize custom key only for string
$custom_key = filter_input(INPUT_GET,'custom_key',FILTER_SANITIZE_STRING);


//if no id passed
if($id == null)
{
	echo '<b>The post id is either not specified or incorrect</b> <br/>';
	exit(0);
}
//check if the post has right status
global $wpdb;
$table_name = $wpdb->prefix.'posts';
$sql = "Select * from  {$table_name} where id=%s and post_status=%s LIMIT 1";
// only published posts are allowed
$sql = $wpdb->prepare($sql, $id, "publish");
$result = $wpdb->query($sql);
if(empty($result))
{
	echo '<b>The post id is not published </b><br/> ';
	exit(0);
}

//check custom key
global $wpdb;
$table_name = $wpdb->prefix.'share_juice';
$sql = "Select * from  {$table_name} where custom_key = %s LIMIT 1";
// only published posts are allowed
$sql = $wpdb->prepare($sql, $custom_key);
$result = $wpdb->query($sql);
if(empty($result))
{
	echo '<b>The Custom_key does not exist </b><br/> ';
	exit(0);
}


// retrieve one post with an ID of 5
query_posts( "p={$id}" );

// set $more to 0 in order to only get the first part of the post
global $more;
$more = 0;

// the Loop
while (have_posts()) : the_post();
global $post,$wpdb;

$button_rec_from_db = BaseShare::get_button_cofiguration($wpdb,$custom_key);
$config = maybe_unserialize($button_rec_from_db['button_config']);

?>

<!DOCTYPE html>
<html lang="<?php echo get_bloginfo('language');?>">
<head>
<title><?php the_title();?></title>
<style type='text/css' media='print'>
#print-button {display : none}
</style>
<?php header('Content-Type: text/html; charset=utf-8'); ?>
<link rel="stylesheet" href="<?php echo SJ_PLUGIN_URL_BASE.'/scripts/print.css'; ?>" type="text/css"/>
<?php if(isset($config['use_theme_css']) && $config['use_theme_css']==1 ) { ?>
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css"/>
	<?php 
}
if(isset($config['use_theme_print_css']) && $config['use_theme_print_css'] == 1 
&& @file_exists(get_stylesheet_directory_uri() .'/print.css')) { ?>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() .'/print.css' ?>" type="text/css"/>
	<?php 
}
?>
</head>
<body>
<div id="share-juice-print-wrapper">
<input type="button"  onClick="window.print()"  value="Print This Page" id="print-button"/>
<div id="share-juice-print-content">
<div id="share-juice-print-post-title">
<h2><?php echo the_title()?></h2>
</div>
<div id="share-juice-print-post-content">
<?php 	 

$content = $post->post_content;
//start processing
if(isset($config['remove_images']) && $config['remove_images']){
	$content = preg_replace("/<img[^>]+\>/i", "", $content); 
}

	
if(isset($config['remove_videos']) && $config['remove_videos'])
{	
	//thank you Lester 'GaMerZ' Chan for this code from wp-print
	$content= preg_replace('/<object[^>]*?>.*?<\/object>/', '',$content);
	$content= preg_replace('/<embed[^>]*?>.*?<\/embed>/', '',$content);
}
echo $content;
?>
</div>
</div>
</div>
<div id ="share-juice-print-footer">

</div>
<?php
endwhile;
?>
</body>	
</html>