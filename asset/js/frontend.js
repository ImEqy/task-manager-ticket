jQuery( document ).ready( function() {
  wpeo_point_ticket.event();
} );

var wpeo_point_ticket = {
  event: function() {
		jQuery( document ).on( 'click', '.wpeo-done-point', function( e ) { wpeo_point_ticket.switch_completed( jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-submit', function() { wpeo_point_ticket.create_point_time( jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-send-mail', function() { wpeo_point_ticket.send_mail( jQuery( this ) ); } );
  },

  switch_completed: function( element ) {
		var point_bloc 	= jQuery( element ).closest( '.wpeo-task-li-point' );
		var point_id	= point_bloc.data( 'id' );
        var task_id = jQuery( element ).data( 'id' );

		point_bloc.removeClass( 'uncomplete' ).addClass( 'complete' );

		var data = {
		  action: 'ticket_edit_point',
		  point: {
			  id: point_id,
			  option: {
				  point_info: {
					  completed: 1
				  }
			  }
		  },
		  task: {
			  id: task_id
		  },
		  _wpnonce: jQuery( element ).data( 'nonce' )
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( element ).closest( '.info' ).replaceWith( this.template );
		} );
  },

  create_point_time: function( element ) {
    var point_bloc = jQuery( element ).closest( '.wpeo-task-li-point' );
    var point_id = point_bloc.data( 'id' );
    var task_id = jQuery( element ).data( 'id' );
    var author_id = jQuery( element ).data( 'author' );
    var date = new Date().toISOString().slice( 0, 10 );
    var content = jQuery( '#wpeo-point-comment' ).val();

    var data = {
      action: 'ticket_create_point_time',
      point_time_id:0,
      point_time: {
          post_id: task_id,
          parent_id: point_id,
          author_id: author_id,
          date: date,
          content: content,
          option: {
              time_info: {
                  elapsed: 0
              }
          }
      },
      _wpnonce: jQuery( element ).data( 'nonce' )
    };

    jQuery( '#wpeo-point-comment' ).val( '' );

    jQuery.eoajax( ajaxurl, data, function() {
			point_bloc.find( '.list-comment' ).replaceWith( this.template );
    } );
  },

	send_mail: function( element ) {
		var task_id = jQuery( element ).closest( '.wpeo-task-li-point' ).data( 'task' );
		var point_id = jQuery( element ).closest( '.wpeo-task-li-point' ).data( 'id' );
		var date = new Date().toISOString().slice( 0, 10 );
		var content = jQuery( '#wpeo-point-comment' ).val();
		var task_bloc = jQuery( element ).closest( '.wpeo-project-task' );
		var data = {
			action: 'my_send_mail',
			task_id: task_id,
			point_id: point_id,
			date: date,
			content: content,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};
		jQuery( '#wpeo-point-comment' ).val( '' );
		jQuery.eoajax( ajaxurl, data, function() {
			point_bloc.find( '.list-comment' ).replaceWith( this.template );
		} );
	}
};
