<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package oceanica-lite
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				printf( esc_html_x( 'One thought to &ldquo;%s&rdquo;', 'comments title', 'oceanica-lite' ), wp_kses_post(get_the_title()) );
			} else {
				printf(
				/* translators: 1: number of comments, 2: post title */
					esc_html(_nx(
						'%1$s Thought to &ldquo;%2$s&rdquo;',
						'%1$s Thoughts to &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						'oceanica-lite'
					)),
					esc_html(number_format_i18n( $comments_number )),
					'<span>' . wp_kses_post(get_the_title()) . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'oceanica-lite' ); ?></h2>
				<div class="nav-links">
					<div
						class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'oceanica-lite' ) ); ?></div>
					<div
						class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'oceanica-lite' ) ); ?></div>
				</div><!-- .nav-links -->
			</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 60,
			) );
			?>
		</ol><!-- .comment-list -->
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'oceanica-lite' ); ?></h2>
				<div class="nav-links">
					<div
						class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'oceanica-lite' ) ); ?></div>
					<div
						class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'oceanica-lite' ) ); ?></div>
				</div><!-- .nav-links -->
			</nav><!-- #comment-nav-below -->
			<?php
		endif; // Check for comment navigation.
	endif; // Check for have_comments().
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'oceanica-lite' ); ?></p>
		<?php
	endif;
	comment_form();	?>
</div><!-- #comments -->