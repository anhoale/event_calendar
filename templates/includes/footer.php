<script>

  $(document).ready(function() {

    var zone = "12:00";  //Local timezone: Auckland/ New Zealand

    var json_events;

    //Prepare json events to initialize the calendar
    $.ajax({
      url: "<?php echo BASE_URI;?>get_event.php",
          type: 'POST', 
          data: 'type=fetch',
          async: false,
          success: function(s){
            json_events = s;
          }
    });


    var currentMousePos = {
        x: -1,
        y: -1
    };
    jQuery(document).on("mousemove", function (e) {
          currentMousePos.x = e.pageX;
          currentMousePos.y = e.pageY;
    });

    /* initialize the external events
    -----------------------------------------------------------------*/

    $('#external-events .fc-event').each(function() {

      // store data so the calendar knows to render an event upon drop
      $(this).data('event', {
        title: $.trim($(this).text()), // use the element's text as the event title
        stick: true // maintain when user navigates (see docs on the renderEvent method)
      });

      // make the event draggable using jQuery UI
      $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
      });

    });

    /* initialize the calendar
    -----------------------------------------------------------------*/

    $('#calendar').fullCalendar({
      events: JSON.parse(json_events),
      //example events: [{"id":"14","title":"New Event","start":"2015-01-24T16:00:00+04:00","allDay":false}],
      utc: true,
      header: {
         left: 'prev,next today',
         center: 'title',
         right: 'month,agendaWeek,agendaDay'
      },
      displayEventEnd: true,
      allDayDefault: true,
      editable: true,
      droppable: true,
      slotDuration: '00:30:00',
      eventRender: function(event, element) {
            element.attr('title', event.title);
      },

    /* When an event is dropped in the calendar the eventReceive event is triggered. 
    *  At this point we have to get the event title, event start date and time, event end date and time.
    *  For any new event, end date is empty, allDay is true
    *
    *   On successful ajax request get the id stored in database and update the id of new event. For the event to take effect, need to update the calendar.
    */
      eventReceive: function(event){
         var title = event.title;
         var start = event.start.format("YYYY-MM-DD[T]HH:MM:SS");   
         $.ajax({
           url: "<?php echo BASE_URI;?>create_event.php",
           data: 'type=create&title='+title+'&start='+start+'&zone='+zone,
           type: 'POST',
           dataType: 'json',
           success: function(response){
             event.id = response.eventid;
             $('#calendar').fullCalendar('updateEvent',event);
           },
           error: function(e){
             console.log(e.responseText);
           }
         });
         $('#calendar').fullCalendar('updateEvent',event);
      }, 
      //Triggered when dragging stops and the event has moved to a different day/time.
       eventDrop: function(event, delta, revertFunc) {
         var title = event.title;
         var start = event.start.format();
         var end = (event.end == null) ? "" : event.end.format();
         //use the length to check if an event is drop from AllDay Section to Time Section (Week view, and Day view)
         var length = start.length;
         if (length === 10) {
          //still in AllDay section
            var allDay = true;
         }
         else if (length === 19) {
          //start date have time section
            var allDay = false;
         }

         $.ajax({
           url: "<?php echo BASE_URI;?>update_event.php",
           data: 'type=updateDateTime&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id+'&allDay='+allDay,
           type: 'POST',
           dataType: 'json',
           success: function(response){
             if(response.status != 'success')
             revertFunc();
           },
           error: function(e){
             revertFunc();
             alert('Error processing your request: '+e.responseText);
          }
          });
      },
      /*Triggered when an event is clicked.
      * Show a popup modal asking the user to update the event details. 
      * 
      */
       eventClick: function(event, jsEvent, view) {
              $("#display_message").hide();
              $.ajax({
                url: "<?php echo BASE_URI;?>get_event.php",
                data: "type=getDetails&eventid="+event.id,
                type: 'POST',
                dataType: 'json',
                success: function(response){
                  
                    var start = Date(response.start);
                    $("#title").val(response.title);
                    $("#start_date").val(response.start);
                    $("#end_date").val(response.end);
                    $('#details').val(response.details);
                    $('#event_id').val(response.id);
                    if (response.allDay == true) {
                      $('#all_day').prop('checked', true);
                    }
                    else {
                       $('#all_day').prop('checked', false);
                    }
                    $("#fullCalModal").modal();
            

                },
                error: function(e) {
                  alert('Error processing your request: '+e.responseText);
                }
              });
             
      },
      /*Triggered when resizing stops and the event has changed in duration.
       Send an ajax request with title, start date, end date and ID of the event to process.php and update the event in the database.
       */
      eventResize: function(event, delta, revertFunc) {
        //console.log(event);

        var title = event.title;
        var start = event.start.format("YYYY-MM-DD[T]HH:mm:ss");
        var end = event.end.format("YYYY-MM-DD[T]HH:mm:ss");
        var allDay = event.allDay;
        $.ajax({
                url: "<?php echo BASE_URI;?>update_event.php",
                data: 'type=updateDateTime&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id+'&allDay='+allDay,
                type: 'POST',
                dataType: 'json',
                success: function(response){  
                  if(response.status == 'success')                
                          $('#calendar').fullCalendar('updateEvent',event);
                },
                error: function(e){
                  alert('Error processing your request: '+e.responseText);
                }
              });
      },
      /*
      *Triggered when event dragging stops.
      *Process removing event if you drag and stop at the Bin icon
      */
      eventDragStop: function (event, jsEvent, ui, view) {
        if (isElemOverDiv()) {
          var con = confirm('Are you sure to delete this event permanently?');
          if(con == true) {
          $.ajax({
              url: "<?php echo BASE_URI;?>remove_event.php",
              data: 'type=remove&eventid='+event.id,
              type: 'POST',
              dataType: 'json',
              success: function(response){
                console.log(response);
                if(response.status == 'success'){
                  $('#calendar').fullCalendar('removeEvents');
                      getFreshEvents();
                    }
              },
              error: function(e){ 
                alert('Error processing your request: '+e.responseText);
              }
            });
          }   
        }
      }
       
    });//close fullCalendar

  function getFreshEvents(){
    $.ajax({
      url: "<?php echo BASE_URI;?>get_event.php",
          type: 'POST', 
          data: 'type=fetch',
          async: false,
          success: function(s){
            freshevents = s;
          }
    });
    $('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
  }
  //check if user drag the event and stop at the trash bin icon
  function isElemOverDiv() {
        var trashEl = jQuery('#trash');

        var ofs = trashEl.offset();

        var x1 = ofs.left;
        var x2 = ofs.left + trashEl.outerWidth(true);
        var y1 = ofs.top;
        var y2 = ofs.top + trashEl.outerHeight(true);

        if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&
            currentMousePos.y >= y1 && currentMousePos.y <= y2) {
            return true;
        }
        return false;
    }
    //Process updating event when user clicks submit on Modal popup
    $("#submit-button").click(function(e){
      e.preventDefault();
      var title = $("#title").val();
      var start = $("#start_date").val();
      var end =   $("#end_date").val();
      var details = $("#details").val();
      var event_id = $("#event_id").val();
      var allDay = $("#all_day").prop('checked')  ? "true" : "false";
      $.ajax({
              url: "<?php echo BASE_URI;?>update_event.php",
              data: 'type=updateAll&title='+title+'&start='+start+'&end='+end+'&eventid='+event_id+'&allDay='+allDay+'&details='+details,
              type: 'POST',
              dataType: 'json',
              success: function(response){  
                //console.log(response);
                if(response.status == 'success')         {     
                  $("#fullCalModal").modal('hide');
                  $('#calendar').fullCalendar('removeEvents');
                  getFreshEvents();

                }
                if(response.status == 'error')         {     
                  //console.log(response);
                 $("#display_message").html('<div class="alert alert-danger">'+ response.error_message+ '</div>');
                 $("#display_message").show();

                }
              },
              error: function(e){
                alert('Error processing your request: '+e.responseText);
              }
            });
    });

    //Process removing event when user clicks Remove on Modal popup
    $("#remove-button").click(function(e){
      e.preventDefault();
      var event_id = $("#event_id").val();

      var con = confirm('Are you sure to delete this event permanently?');
      if(con == true) {
        $.ajax({
              url: "<?php echo BASE_URI;?>process_event.php",
              data: 'type=remove&eventid='+event_id,
              type: 'POST',
              dataType: 'json',
              success: function(response){  
                //console.log(response);
                if(response.status == 'success')         {     
                  $("#fullCalModal").modal('hide');
                  $('#calendar').fullCalendar('removeEvents');
                  getFreshEvents();

                }
                if(response.status == 'error')         {     
                  //console.log(response);
                 $("#display_message").html('<div class="alert alert-danger">'+ response.error_message+ '</div>');
                 $("#display_message").show();

                }
              },
              error: function(e){
                alert('Error processing your request: '+e.responseText);
              }
        });
      }
    });
    //Initialize date picker for Start Date and End Date fields in the modal
    $( "#start_date" ).datetimepicker({format:'YYYY-MM-DD[T]HH:mm:ss'});
    $( "#end_date" ).datetimepicker({format:'YYYY-MM-DD[T]HH:mm:ss'});


  });

</script>
  </body>
</html>