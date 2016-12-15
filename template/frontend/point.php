<?php
/**
 * Affiche les tickets
 *
 * @package Ticket
 */
?>

<li class="wpeo-ticket <?php echo esc_html( $valid_ticket ); ?> wpeo-task-li-point" data-id="<?php echo $point->id; ?>">
	<span class="id"><?php echo esc_html( '#' . $task->id ); ?></span>
	<a href="?account_dashboard_part=my-task-comments&task_id=<?php echo esc_html( $task->id ); ?>&point_id=<?php echo esc_html( $point->id ); ?>">
		<span class="task-subject"><?php echo esc_html( $task->title ); ?></span>
		<span class="point-subject"><?php echo esc_html( ' - ' . $point->content ); ?></span>
	</a>
	<span class="comment"><?php printf( esc_html__( '(%d comments)', 'task-manager' ), $count_comment ); ?></span>
	<span><i class="fa fa-clock-o" title="<?php esc_html_e( 'Elapsed time', 'task-manager' ); ?>"></i> <?php echo $point->option['time_info']['elapsed']; ?></span>
	<span class="right">
		<span class="update">
			<?php
			$last_edit = ! empty( $last_comment ) ? $last_comment->date : $point->date;
			printf( esc_html__( '%s ago', 'task-manager' ), human_time_diff( date_timestamp_get( date_create( $last_edit ) ), current_time( 'timestamp' ) ) );
			?>
		</span>
		<span class="action">
			<?php if ( 'complete' !== $valid_ticket ) : ?>
				<i class="fa fa-check complete wpeo-done-point" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_edit_point_' . $point->id ); ?>"></i>
			<?php endif; ?>
		</span>
	</span>
</li>
