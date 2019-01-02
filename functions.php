<?php

if (!defined('MAVITM_THEME_VERSION')) {
	define('MAVITM_THEME_VERSION', '1.0');
}if (!defined('OZELALAN')) {
	$ozelalan = get_option('MaviTmOzelAlan');
	if(empty($ozelalan)){
		define('OZELALAN', 'thumb',true);
	}else{
		define('OZELALAN', $ozelalan,true);
	}
}

require_once(get_template_directory().'/widgets/MaviTmWidgets.php');


if ( ! function_exists( 'mavitm_setup' ) ){
	function mavitm_setup() {
	
		/*
		load_theme_textdomain( 'mavitm', TEMPLATEPATH . '/languages' );
	
		$locale = get_locale();
		$locale_file = TEMPLATEPATH . "/languages/$locale.php";
		if ( is_readable( $locale_file ) )
			require_once( $locale_file );
	
		add_theme_support( 'automatic-feed-links' );
		*/
		register_nav_menus( array( 
			"primary" => __( "Kategori Menu - header ", "MaviTm" ),
			"primary1" => __( "Ust Menu - header kategori menu ustu ", "MaviTm" ), 
			"primary2" => __( "Top Menu - Top Menu", "MaviTm" ), 
			"primary3" => __( "KategoriMenu - footer", "MaviTm" ),
			"primary4" => __( "Alt Menu - footer kategori menu alti ", "MaviTm" ),
		) );
	
		$widgets = new MaviTmWidgets();
		$widgets->init();
		
	}
} // mavitm_setup

require_once(get_template_directory().'/panel/ayarlar.php');

function mavitm_widgets_init() {

	if ( function_exists('register_sidebar') ){
	    require_once(get_template_directory().'/view/sidebar.php');
	}

}

function MaviTmscripts_method() {
	$file_dir = get_bloginfo('template_directory');
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', $file_dir.'/js/jquery.js',false,'1.7.2');
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script("colorbox", $file_dir."/js/jquery.colorbox.js", false, "1.3.19");
    wp_enqueue_script("MaviTm", $file_dir."/js/mavitm.js", false, "1.0");
    if(get_option('MaviTmAddthisOn')){
    	wp_enqueue_script("addThis", 'http://s7.addthis.com/js/250/addthis_widget.js#pubid='.(get_option('MaviTmAddthisUser') ? get_option('MaviTmAddthisUser') : 'mavitm').'', false, "1.0");
    }
}    
 
add_action('wp_enqueue_scripts', 'MaviTmscripts_method');
add_action( 'after_setup_theme', 'mavitm_setup' );
add_action( 'widgets_init', 'mavitm_widgets_init' );

add_theme_support( "post-thumbnails" );
add_theme_support("automatic-feed-links");
add_theme_support('post-formats', array('gallery','video'));

  
function MaviTmEditorAdd3($buttons) { /* Araç çubuğunun 3. satırına eklenecek düğmeler */
    array_push($buttons, "anchor", "backcolor","cleanup", "code", "copy", "cut", "paste", "hr", "newdocument", "redo", "sub", "sup", "undo", "fontselect", "fontsizeselect", "styleselect");
	return $buttons;
}
add_filter("mce_buttons_3", "MaviTmEditorAdd3");



if ( ! function_exists( 'mavitm_comment' ) ) :
function mavitm_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
	<p><?php _e( 'Pingback:', 'MaviTm' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'MaviTm' ), '<span class="edit-link">', '</span>' ); ?></p>
	
	<?php break; default :
	?>
	<li id="li-comment-<?php comment_ID(); ?>">
	
		<div article id="comment-<?php comment_ID(); ?>" class="comment">
			
				<div class="comment-author vcard">
					<div class="avtArr"></div>
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s  %2$s ', 'MaviTm' ),
							sprintf( '<span class="kisi">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s" class="comDate"><time pubdate datetime="%2$s">%3$s</time></a>',esc_url( get_comment_link( $comment->comment_ID ) ),get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s - %2$s', 'MaviTm' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Duzenle', 'MaviTm' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Yorumunuz onayland&#305;ktan sonra yay&#305;nlan&#305;cak.', 'MaviTm' ); ?></em>
					<br />
				<?php endif; ?>

			<div class="comment-content"><?php comment_text(); ?></div>
			
			<div class="clear"></div>
			
			<div class="replyy">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Cevap Ver', 'MaviTm' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
			
			<div class="clear"></div>
		</div>

	<?php
			break;
	endswitch;
}
endif; // ends check for twentyeleven_comment()

?>