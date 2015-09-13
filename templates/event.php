<?php include 'includes/header.php'; ?>
<body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">My calendar</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<div id="container">
	<div id='wrap'>

		<div id='external-events'>
			<h4>Draggable Events</h4>
			<div class='fc-event'>New Event</div>
			<p>
				<img src="<?php echo BASE_URI;?>templates/img/trashcan.png" id="trash" alt="">
			</p>
		</div>

		<div id='calendar'></div>

		<div style='clear:both'></div>


	</div>

	<div id="fullCalModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Event Details</h3>
				<div id="display_message"></div>
			</div>

			<div class="modal-body">
				<form method="post" accept-charset="utf-8">
				  <div class="form-group">
				    <label>Title</label>
				    <input type="text" class="form-control" id="title" name="title" placeholder="">
				  </div>
				  <div class="form-group">
				    <label>Start</label>
				    <input type="text" class="form-control" id="start_date" name="start_date" placeholder="">
				  </div>
				  <div class="form-group">
				    <label>End</label>
				    <input type="text" class="form-control" id="end_date" name="end_date" placeholder="">
				  </div>
				  <div class="form-group">
				   	<label>All Day Event</label>
				    <input type="checkbox" id="all_day">
				  </div>
				  <div class="form-group">
				    <label>Details</label>
				    <textarea id="details" rows="6" cols="80" class="form-control" name="details" placeholder="Put event details here."></textarea>
				  </div>

				   <div class="form-group">
				   <input type="hidden" name="event_id" id="event_id">
				   	<label> </label>
				    <button type="submit" name="submit" class="btn btn-primary" id="submit-button">Submit</button>
				     <button type="submit" name="remove" class="btn btn-warning" id="remove-button">Remove</button>
				   </div>
				</form>

			</div>

	    <div class="modal-footer">
	        <p>&copy; An LE 2015</p>

	    </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
 	</div><!-- ./fullCalModal -->
</div><!-- /.container -->
<?php include 'includes/footer.php'; ?>