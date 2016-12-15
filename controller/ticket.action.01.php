<?php if (!defined('ABSPATH')) {
    exit;
}

class ticket_action_01
{
    public function __construct()
    {
		add_action( 'wp_ajax_ticket_edit_point', array( $this, 'ajax_ticket_edit_point' ) );
		add_action( 'wp_ajax_ticket_create_point_time', array( $this, 'ajax_ticket_create_point_time' ) );
    }
	public function ajax_ticket_edit_point() {
		wpeo_check_01::check( 'wpeo_nonce_edit_point_' . $_POST['point']['id'] );
		global $point_controller;
		global $task_controller;
		global $time_controller;
		global $history_time_controller;

		$point = $point_controller->show( $_POST['point']['id'] );

		if( $_POST['point']['option']['point_info']['completed'] ) {
			$point->option['time_info']['completed_point'][get_current_user_id()][] = current_time( 'mysql' );
		}
		else {
			$point->option['time_info']['uncompleted_point'][get_current_user_id()][] = current_time( 'mysql' );
		}

		$_POST['point']['option']['time_info']['completed_point'] = $point->option['time_info']['completed_point'];
		$_POST['point']['option']['time_info']['uncompleted_point'] = $point->option['time_info']['uncompleted_point'];

		$point_controller->update( $_POST['point'] );

		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $_POST['point']['id'],
			'message' => sprintf( __( 'The point #%d was updated with the content : %s and set to completed : %s', 'task-manager'), $_POST['point']['id'], $_POST['point']['content'], $_POST['point']['option']['point_info']['completed'] ),
		), 0 );
		$point = $point_controller->show( $point->id );
		$task = $task_controller->show( $_POST['task']['id'] );
		$list_time = $time_controller->index( $task->id, array( 'orderby' => 'comment_date', 'order' => 'ASC', 'parent' => $point->id, 'status' => -34070 ) );
		ob_start();
			require( wpeo_template_01::get_template_part( WPEO_TICKET_DIR, WPEO_TICKET_TEMPLATES_MAIN_DIR, 'frontend', 'point', 'statut' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}
	public function ajax_ticket_create_point_time() {
		global $time_controller;
		global $point_controller;
		global $ticket_controller;

		$response = array();

		$_POST['point_time']['date'] .= ' ' . current_time( 'H:i:s' ); //$_POST['point_time']['time']

		if ( !empty( $_POST['point_time_id'] ) ) {
			/** Edit the point */
			$point_time = $time_controller->show( $_POST['point_time_id'] );
			$point_time->option['time_info']['old_elapsed'] = $point_time->option['time_info']['elapsed'];
			$point_time->date = $_POST['point_time']['date'];
			$point_time->option['time_info']['elapsed'] = $_POST['point_time']['option']['time_info']['elapsed'];
			$point_time->content = $_POST['point_time']['content'];

			$list_object = $time_controller->update($point_time);
		}
		else {
			/** Add the point */
			$_POST['point_time']['status'] = '-34070';
			$list_object = $time_controller->create( $_POST['point_time'] );
		}

		$point = $point_controller->show( $_POST['point_time']['parent_id'] );
		$time = $list_object['time'];
		$task = $list_object['task'];

		ob_start();
			?><ul class="list-comment">
				<?php echo $ticket_controller->list_time( $time_controller->index( $_POST['point_time']['post_id'], array( 'orderby' => 'comment_date', 'order' => 'ASC', 'parent' => $point->id, 'status' => -34070 ) ) ); ?>
			</ul><?php
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}
}

global $ticket_action;
$ticket_action = new ticket_action_01();
