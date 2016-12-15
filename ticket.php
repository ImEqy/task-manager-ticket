<?php
/**
 * Plugin Name: Task Manager Tickets
 * Description: Gestion des tickets avec Task Manager
 * Version: 1.0.0.0
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Ticket
 */

/**
 * Gestion des tickets
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

/**
 * TODO
 * Trouver une autre solution pour "task-manager/taskmanager.php" et "?account_dashboard_part=my-task-comments"
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'plugins_loaded', 'dependence_check' );

function dependence_check() {
	if ( ! function_exists( 'is_plugin_active' ) || ! is_plugin_active( 'task-manager/taskmanager.php' ) ) {
		return;
	}

	/**
	 * Define
	 */
	DEFINE( 'WPEO_TICKET_VERSION', '1.0.0.0' );
	DEFINE( 'WPEO_TICKET_DIR', basename( dirname( __FILE__ ) ) );
	DEFINE( 'WPEO_TICKET_PATH', str_replace( '\\', '/', plugin_dir_path( __FILE__ ) ) );
	DEFINE( 'WPEO_TICKET_URL', str_replace( str_replace( '\\', '/', ABSPATH ), site_url() . '/', WPEO_TICKET_PATH ) );

	DEFINE( 'WPEO_TICKET_ASSETS_DIR',  WPEO_TICKET_PATH . '/asset/' );
	DEFINE( 'WPEOMTM_TICKET_ASSETS_URL',  WPEO_TICKET_URL . '/asset/' );
	DEFINE( 'WPEO_TICKET_TEMPLATES_MAIN_DIR', WPEO_TICKET_PATH . '/template/' );

	require_once( WPEO_TICKET_PATH . '/controller/ticket.controller.01.php' );
	require_once( WPEO_TICKET_PATH . '/controller/ticket.action.01.php' );
}
