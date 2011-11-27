<?php 
function theme_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment_wrap">
			<div class="gravatar"><?php echo get_avatar($comment,$size='60',$default=''); ?></div>
			<div class='comment_content'>
				<div class="comment_meta">
					<?php printf( '<cite class="comment_author">%s</cite>', get_comment_author_link()) ?><?php edit_comment_link(__('(Edit)', 'striking_front' ),'  ','') ?>
					<time class="comment_time"><?php echo get_comment_date(); ?></time>
				</div>
				<div class='comment_text'>
					<?php comment_text() ?>
<?php if ($comment->comment_approved == '0') : ?>
					<span class="unapproved"><?php _e('Your comment is awaiting moderation.','striking_front') ?></span>
<?php endif; ?>
				</div>
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
			</div>
		</div>
<?php
}
?>
<section id="comments">
<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'striking_front' ); ?></p>
</section><!-- #comments -->
<?php
		return;
	endif;
if ( have_comments() ) : ?>
	<h3 id="comments_title"><?php
	printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'striking_front' ),
	number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
	?></h3>
	<ul class="commentlist">
		<?php
			wp_list_comments( array( 'callback' => 'theme_comments' ) );
		?>
	</ul>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
	<nav class="comments_navigation">
		<div class="nav_previous"><?php previous_comments_link(); ?></div>
		<div class="nav_next"><?php next_comments_link(); ?></div>
	</nav>
<?php endif; // check for comment navigation ?>
<?php else : // or, if we don't have comments:
	/* If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if ( ! comments_open() ) :
	/*<p class="nocomments"><?php _e( 'Comments are closed.', 'striking_front' ); ?></p>*/
?>
<?php endif; // end ! comments_open() ?>
<?php endif; // end have_comments() ?>
<?php if ( comments_open() ) :// Comment Form ?>
	<div id="respond">
		<h3><?php comment_form_title( __('Leave a Reply','striking_front'), __('Leave a Reply to %s','striking_front') ); ?></h3>
    	<div class="cancel_comment_reply">
        	<?php cancel_comment_reply_link(); ?>
		</div>
<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
		<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment','striking_front'),wp_login_url( get_permalink() )); ?></p>
<?php else : ?>
   		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( is_user_logged_in() ) : ?>
			<p class="logged"><?php printf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>','striking_front'), admin_url( 'profile.php' ), $user_identity, wp_logout_url( get_permalink()  ) )?></p>
<?php else : ?>	
			<p><input type="text" name="author" class="text_input" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1"  />
			<label for="author"><?php _e('Name','striking_front');  if ($req) echo " *"; ?></label></p>
			<p><input type="text" name="email" class="text_input" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
			<label for="email"><?php _e('Email','striking_front');  if ($req) echo " *"; ?></label></p>
			<p><input type="text" name="url" class="text_input" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
			<label for="url"><?php _e('Website','striking_front'); ?></label></p>
<?php endif; ?>
			<p><textarea class="textarea" name="comment" id="comment" cols="70" rows="10" tabindex="4"></textarea></p>
			<p><a class="button medium white" href="#" onclick="jQuery('#commentform').submit();return false;"><span><?php _e('Post Comment','striking_front');?></span></a><?php comment_id_fields(); ?></p>
			<p><?php do_action('comment_form', $post->ID); ?></p>
		</form>
<?php endif; // If registration required and not logged in ?>
	</div><!--/respond-->
<?php endif; ?>
</section><!-- #comments -->
