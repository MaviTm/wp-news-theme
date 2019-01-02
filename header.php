<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
	<head profile="http://gmpg.org/xfn/11">
		<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<?php if(get_option('MaviTmCssCompress')){?>
		<link rel="stylesheet" href="<?php bloginfo('template_directory');?>/css.php" type="text/css" media="screen,projection" />	
		<?php }else{?>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen,projection" />
		<link rel="stylesheet" href="<?php bloginfo('template_directory');?>/widget.css" type="text/css" media="screen,projection" />
		<?php }?>
		
		<?php wp_head(); ?>
		
		<?php
		if(is_single()){ 
			if(has_post_thumbnail()) {
				$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail');
				echo '<meta property="og:image" content="'.$large_image_url[0].'" />';
			}elseif(get_post_meta($post->ID, "thumb", true)!=""){
				echo '<meta property="og:image" content="'.get_post_meta($post->ID, "thumb", true).'" />';
			} else {
				//echo '<meta property="og:image" content="'.stripslashes(get_option('kuaza_sitelogusue')).'" />';
			}
		?>
		<meta property="og:title" content="<?php wp_title('&amp;laquo;', true, 'right'); ?> | <?php bloginfo('name'); ?>" />
		<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
		<meta property="og:url" content="<?php the_permalink() ?>" />
		<meta property="og:type" content="<?php if( get_post_meta($post->ID, "type", true) ): echo get_post_meta($post->ID, "type", true); else: echo "article"; endif;?>" />
		<?php  } ?>
	<!--7adf106c59d54fb78392c779f1d744c7-->

	<meta http-equiv="refresh" content="260" />
	<meta name='yandex-verification' content='599fc825879f4a7b' />		
		
	</head>
	<body <?php //body_class(); ?>>
	<div id="wrapper">
	
		<div id="header">
			<div class="headTop">
				<div class="topMenu">
					<?php wp_nav_menu( array('theme_location' => 'primary2', 'container_class' => 'menu-top-menu' )); ?>
				</div>
			</div>
			<div class="logo"><a href="<?php bloginfo('url');?>" title="<?php bloginfo('name'); ?>">
				<?if(get_option('MaviTmLogo')){?><img src="<?=get_option('MaviTmLogo');?>" alt="" title="" /><?php }else{ ?><img src="<?php bloginfo('template_directory');?>/images/logo.png" alt="" title="" /><?php }?>
			</a></div>
			
			
			
			<div class="headBanner">
				<?php dynamic_sidebar('logoyani'); ?>
			</div>
			<div class="clear"></div>
			
			<div id="ustMenu">
				<?php wp_nav_menu(array('theme_location' => 'primary1', 'container_class' => 'menuUst')); ?>
				<div class="maviTmSearch">
					<?php get_search_form(); ?>
				</div>
			</div>
			<div id="access" role="navigation">
				<?php wp_nav_menu(array('theme_location' => 'primary')); ?>
			</div>
			
			<div class="clear"></div>
		</div>

		
		