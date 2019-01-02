<?php
/**
 * www.mavitm.com
 */
?>
	<div id="comments">
	<?php if ( post_password_required()  && $_COOKIE['wp-postpass_'.COOKIEHASH]!=$post->post_password) : ?>
		<p class="nopassword"><?php _e( 'Giri&#351; yapmal&#305;s&#305;n&#305;z', 'mavitm' ); ?></p>
	</div><!-- #comments -->
	<?php return; endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through 
		/*
		<div id="comment-nav-above">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'mavitm' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'mavitm' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'mavitm' ) ); ?></div>
		</div>
		*/ endif; // check for comment navigation ?>

		<ul class="commentlist">
			<?php
				wp_list_comments( array( 'callback' => 'mavitm_comment' ) );
			?>
		</ul>
		
		

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<div class="clear"></div>
		<div id="comment-nav-below">
			<h3 class="assistive-text"><?php _e( 'Di&#287;er Yorumlar', 'mavitm' ); ?></h3>	
			<div class="nav-next"><?php next_comments_link( __( 'Bir Sonraki &raquo;', 'mavitm' ) ); ?></div>
			<div class="nav-previous"><?php previous_comments_link( __( '&laquo; Bir &Ouml;nceki', 'mavitm' ) ); ?></div>
		</div>
		<div class="clear"></div>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( '', 'mavitm' ); //yorumlar kapali?></p>
	<?php endif; ?>
	<div id="yorumYapmakIstiyor"></div>
	<?php comment_form(); ?>

</div>
