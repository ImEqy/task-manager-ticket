<?php

if ( ! defined( 'ABSPATH' ) ) { exit;
}

if ( ! class_exists( 'ticket_controller_01' ) ) {
	class ticket_controller_01 {
		public function __construct() {
	    	add_shortcode( 'ticket', array( $this, 'callback_shortcode_ticket' ) );
	    	add_shortcode( 'ticket_comment', array( $this, 'callback_shortcode_ticket_comment' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_front' ) );
		}
		public function enqueue_scripts_front() {
			wp_enqueue_script( 'wpeo-ticket-frontend-js', WPEOMTM_TICKET_ASSETS_URL . '/js/frontend.js', array( 'jquery' ), WPEO_TICKET_VERSION );
			wp_enqueue_style( 'wpeo-ticket-frontend-css', WPEOMTM_TICKET_ASSETS_URL . '/css/frontend.css' );
		}
		public function callback_shortcode_ticket( $args ) {
			global $task_controller;
			global $point_controller;
			global $time_controller;
			$list_task = $task_controller->index( apply_filters( 'ticket_query_shortcode', array(
					'post_parent' => 0,
					'meta_query' => array(
						array(
							'key' => 'wpeo_task',
							'value' => '{"user_info":{"owner_id":' . get_current_user_id(),
							'compare' => 'like',
						),
					),
			) ) );
	  		ob_start();
			require( wpeo_template_01::get_template_part( WPEO_TICKET_DIR, WPEO_TICKET_TEMPLATES_MAIN_DIR, 'frontend', 'list', 'point' ) );
			return ob_get_clean();
		}
		public function callback_shortcode_ticket_comment( $args ) {
			if ( ! isset( $args['task_id'] ) || ! isset( $args['point_id'] ) ) {
				return;
			}
			global $task_controller;
			global $point_controller;
			global $time_controller;
			global $history_time_controller;
			$task = $task_controller->show( $args['task_id'] );
			$point = $point_controller->show( $args['point_id'] );
			$list_time = $time_controller->index( $task->id, array( 'orderby' => 'comment_date', 'order' => 'ASC', 'parent' => $point->id, 'status' => -34070 ) );
			ob_start();
				require( wpeo_template_01::get_template_part( WPEO_TICKET_DIR, WPEO_TICKET_TEMPLATES_MAIN_DIR, 'frontend', 'list', 'time' ) );
			return ob_get_clean();
		}
		public function list_time( $list_time ) {
			ob_start();
			if ( ! empty( $list_time ) ) :
				foreach ( $list_time as $time ) :
					require( wpeo_template_01::get_template_part( WPEO_TICKET_DIR, WPEO_TICKET_TEMPLATES_MAIN_DIR, 'frontend', 'time' ) );
				endforeach;
			else :
				require( wpeo_template_01::get_template_part( WPEO_TICKET_DIR, WPEO_TICKET_TEMPLATES_MAIN_DIR, 'frontend', 'no', 'time' ) );
			endif;
			return ob_get_clean();
		}
	}

	global $ticket_controller;
	$ticket_controller = new ticket_controller_01();
}
