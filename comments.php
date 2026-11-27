<?php
/**
 * 极简瑞士网格评论模板（comments.php）
 *
 * @package SkillCraft
 */

if ( post_password_required() ) {
	return;
}
?>
<div class="sc-comments">
	<h3>
		<?php
		$cnt = get_comments_number();
		echo esc_html( $cnt ? sprintf( sc_t( '评论讨论（%d）', 'Comments (%d)' ), $cnt ) : sc_t( '评论讨论', 'Comments' ) );
		?>
	</h3>

	<?php if ( have_comments() ) : ?>
		<ol class="sc-comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 36,
			) );
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	if ( comments_open() ) {
		comment_form( array(
			'title_reply'          => sc_t( '发表评论', 'Leave a comment' ),
			'label_submit'         => sc_t( '提交评论', 'Post comment' ),
			'class_submit'         => 'sc-btn sc-btn--dark',
			'comment_notes_before' => '',
		) );
	}
	?>
</div>
