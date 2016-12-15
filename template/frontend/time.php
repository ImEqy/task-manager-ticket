<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php
global $wp_project_user_controller;
$user = $wp_project_user_controller->show( $time->author_id );
?>

<li class="comment comment-<?php echo esc_html( $time->id ); ?>" data-id="<?php esc_html( $time->id ); ?>">
	<span class="avatar">
		<?php echo get_avatar( $time->author_id, 40, 'blank' ); ?>
		<span class="wpeo-avatar-initial" style="background-color: #<?php echo $user->option['user_info']['avatar_color']; ?>"><?php echo strtoupper( $user->option['user_info']['initial'] ); ?></span>
	</span>
	<span class="author"><?php echo esc_html( $user->displayname ); ?></span>
	<span class="date">
		<span><?php esc_html_e( 'On', 'task-manager' ); ?> </span>
		<span><?php esc_html( comment_date( get_option( 'date_format' ), $time->id ) ); ?></span>
		<span><?php esc_html_e( 'at', 'task-manager' ); ?></span>
		<span><?php esc_html( comment_date( get_option( 'time_format' ), $time->id ) ); ?></span>
	</span>
	<span class="content"><?php echo esc_html( stripslashes( $time->content ) ); ?></span>
</li>
