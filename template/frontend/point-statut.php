<?php
if( !empty( $task->option['time_info']['history_time'] ) ) {
	$history_time = $history_time_controller->show( $task->option['time_info']['history_time'] );
	$buy_time = $history_time->option['estimated_time'];
} else {
	$buy_time = 0;
}
$count_comment = count( $list_time );
$valid_ticket = $point->option['point_info']['completed'] ? 'complete' : 'uncomplete';
$valid_ticket_string = ( 'complete' === $valid_ticket ) ? __( 'Complete', 'task-manger' ) : __( 'In progress', 'task-manger' ); ?>
<div class="info">
	<span class="statut <?php echo esc_html( $valid_ticket ); ?>"><?php echo esc_html( $valid_ticket_string ); ?></span>
	<span class="comment"><?php printf( esc_html__( '%d comments', 'task-manager' ), $count_comment ); ?></span>
	<span class="time"><i class="fa fa-clock-o"></i><?php printf( esc_html__( ' Time spend on your ticket : %d/%d', 'task-manager' ), $point->option['time_info']['elapsed'], $buy_time ); ?></span>
	<?php if ( 'complete' !== $valid_ticket ) : ?>
		<div class="check wpeo-done-point" data-id="<?php echo $task->id; ?>" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_edit_point_' . $point->id ); ?>">
			<i class="fa fa-check"></i>
			<span><?php esc_html_e( 'Tag ticket as complete', 'task-amanager' ); ?></span>
		</div>
	<?php endif; ?>
</div>
