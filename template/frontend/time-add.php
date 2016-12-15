<?php if (!defined('ABSPATH')) {
    exit;
}
?>

<h3>
	<i class="fa fa-mail-reply"></i>
	<span><?php esc_html_e( 'Reply', 'task-manager' ); ?></span>
</h3>

<div class="wpeo-task-form-input">
	<div>
		<textarea id="wpeo-point-comment" placeholder="<?php esc_html_e('Write an answer...', 'task-manager');?>"></textarea>
		<input type="button" class="submit wpeo-submit" value="<?php esc_html_e( 'Add a comment', 'task-manager' ); ?>" data-id="<?php echo $task->id; ?>" data-author="<?php echo get_current_user_id(); ?>" data-nonce="<?php echo wp_create_nonce('wpeo_nonce_create_point_time_' . $point->id); ?>">
	</div>
</div>
