<?php if (!defined('ABSPATH')) {
		exit;
}

class ticket_action_01
{
		public function __construct()
		{
		add_action( 'wp_ajax_ticket_edit_point', array( $this, 'ajax_ticket_edit_point' ) );
		add_action( 'wp_ajax_ticket_create_point_time', array( $this, 'ajax_ticket_create_point_time' ) );
		add_action( 'wp_ajax_my_send_mail', array( $this, 'ajax_my_send_mail' ) );
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
			$point->option['time_info']['Uncompleted_point'][get_current_user_id()][] = current_time( 'mysql' );
		}

		$_POST['point']['option']['time_info']['completed_point'] = $point->option['time_info']['completed_point'];
		$_POST['point']['option']['time_info']['Uncompleted_point'] = $point->option['time_info']['Uncompleted_point'];

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
	public function ajax_my_send_mail() {
		//	wpeo_check_01::check( 'wpeo_send_mail_task_' . $_POST['id'] );

		global $task_controller;
		global $point_controller;
		global $time_controller;
		$task 				= $task_controller->show( $_POST['task_id'] );
		$point 				= $point_controller->show( $_POST['point_id'] );
		$comment			= $_POST['submit'];

		$sender_data = wp_get_current_user();
		$multiple_recipients = array();

		if ( ! empty( $point->option['user_info']['affected_id'] ) ) {
			foreach ( $point->option['user_info']['affected_id'] as $user_id ) {
				$user_info = get_userdata( $user_id );
				$multiple_recipients[] = $user_info->user_email;
			}
		}

		$subject = 'Task Manager: ';
		$subject .= __( 'Commentaire suite au ticket #' . $task->id . ' ' . $task->title, 'task-manager' );
		$body = __( '<p>Ce mail est envoyé automatiquement</p>' , 'task-manager' );
		$body .= '<h2>'. 'Ticket n°' . $task->id . ' ' . $task->title . ' Envoyé par : ' . $sender_data->user_login . ' (' . $sender_data->user_email . ')</h2>';
		$body = apply_filters( 'task_points_mail', $body, $task );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$message = '<p>' . 'Voici le commentaire laissé :' . $submit . '</p>';
		$admin_email = get_bloginfo( 'admin_email' );
		$blog_name = get_bloginfo( 'name' );

		$headers[] = 'From: ' . $blog_name . ' <' . $admin_email . '>';

		wp_mail( array( 'ReasonEQ@gmail.com' ), $subject , $body , array( 'Content-Type: text/html; charset=UTF-8', $headers, $message, $mail ) );

		wp_send_json_success();
	}
}

global $ticket_action;
$ticket_action = new ticket_action_01();
