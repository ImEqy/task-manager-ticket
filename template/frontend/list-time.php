<?php
/**
 * Detail of ticket
 *
 * @package ticket
 */

?>

<div class="wpeo-detail-ticket wpeo-task-li-point" data-id="<?php echo $point->id; ?>" data-task="<?php echo $task->id; ?>">
	<h2><?php echo esc_html( '#' . $task->id . ' ' . $task->title . ' - ' . $point->content ); ?></h2>
	<?php require( wpeo_template_01::get_template_part( WPEO_TICKET_DIR, WPEO_TICKET_TEMPLATES_MAIN_DIR, 'frontend', 'point', 'statut' ) ); ?>

	<ul class="list-comment">
		<?php echo $this->list_time( $list_time ); ?>
	</ul>

	<div class="add-comment">
		<?php	require( wpeo_template_01::get_template_part( WPEO_TICKET_DIR, WPEO_TICKET_TEMPLATES_MAIN_DIR, 'frontend', 'time', 'add' ) ); ?>
	</div>
</div>
