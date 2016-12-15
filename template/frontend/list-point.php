<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<ul class="wpeo-list-ticket">
<?php
if ( !empty ( $list_task ) ) {
	foreach ( $list_task as $key => $task ) {
		if ( !empty( $task->option['task_info']['order_point_id'] ) ) {
			$list_point = $point_controller->index( $task->id, array( 'orderby' => 'comment__in', 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );
			$sort_by_complete = array( 'completed' => array(), 'Uncompleted' => array() );
			foreach ( $list_point as $point ) {
				if( $point->option['point_info']['completed'] ) {
					$sort_by_complete['completed'][] = $point;
				} else {
					$sort_by_complete['Uncompleted'][] = $point;
				}
			}
			$list_point = array_merge( $sort_by_complete['Uncompleted'], $sort_by_complete['completed'] );
			foreach ( $list_point as $point ) {
				$comments = $time_controller->index( $task->id, array( 'orderby' => 'comment_date', 'parent' => $point->id, 'status' => -34070 ) );
				$count_comment = count( $comments );
				$valid_ticket = $point->option['point_info']['completed'] ? 'complete' : 'uncomplete';
				require( wpeo_template_01::get_template_part( WPEO_TICKET_DIR, WPEO_TICKET_TEMPLATES_MAIN_DIR, 'frontend', 'point' ) );
			}
		}
	}
}
?>
</ul>
