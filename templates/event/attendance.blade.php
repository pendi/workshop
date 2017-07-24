@extends('skeleton')

@section('back')
    <ul class="flat left">
        <li><a href="{{ f('controller.url', '/'.$dataEvent['category'].'/listEvent') }}"><i class="xn xn-left-open"></i>{{ l('Back') }}</a></li>
    </ul>
@stop

@section('content')
	<style>
	/* The Modal (background) */
	.modal {
	    display: none; /* Hidden by default */
	    position: fixed; /* Stay in place */
	    z-index: 1; /* Sit on top */
	    padding-top: 100px; /* Location of the box */
	    left: 0;
	    top: 0;
	    width: 100%; /* Full width */
	    height: 100%; /* Full height */
	    overflow: auto; /* Enable scroll if needed */
	    background-color: rgb(0,0,0); /* Fallback color */
	    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}

	/* Modal Content */
	.modal-content {
	    background-color: #fefefe;
	    margin: auto;
	    padding: 20px;
	    border: 1px solid #888;
	    width: 80%;
	}

	/* The Close Button */
	.close {
	    color: #aaaaaa;
	    float: right;
	    font-size: 28px;
	    font-weight: bold;
	}

	.close:hover,
	.close:focus {
	    color: #000;
	    text-decoration: none;
	    cursor: pointer;
	}
	</style>

	<div class="wrapper">
		<paper-detail>
			<h2 class="subtitle">Event Detail</h2>
			<div class="row">
				<div class="span-6">
					<row>
						<label>Name</label>
						<info><?php echo $dataEvent['name'] ?></info>
					</row>
					<row>
						<label>Date</label>
						<info><?php echo date("d F Y", strtotime($dataEvent['date'])) ?></info>
					</row>
				</div>
				<div class="span-6">
					<row>
						<label>Title</label>
						<info><?php echo $dataEvent['title'] ?></info>
					</row>
					<row>
						<label>Category</label>
						<info><?php echo $dataEvent->format('category') ?></info>
					</row>
				</div>
			</div>
			<h2 class="subtitle">Participants</h2>
			<div class="row">
				<div class="table-container">
					<table class="nowrap">
						<thead>
							<tr>
								<th>Name</th>
								<th>Time</th>
								<th>Status</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($dataUser as $user): ?>
								<tr>
									<td><?php echo $user['first_name'].' '.$user['last_name'] ?></td>
									<td id="time-<?php echo $user['$id'] ?>" <?php echo !empty($user['time']) ? 'style="background-color: ' .$user['status_color']. ';"' : '' ?>><?php echo $user['time'] ?></td>
									<td id="status-<?php echo $user['$id'] ?>" <?php echo !empty($user['time']) ? 'style="background-color: ' .$user['status_color']. ';"' : '' ?>><?php echo $user['status'] ?></td>
									<td style="text-align: right">
										<?php if (empty($user['time'])): ?>
											<span id="btnTime-<?php echo $user['$id'] ?>" style="color: green; cursor: pointer;" class="xn-clock" onclick="time('<?php echo $user['$id'] ?>')"></span>
										<?php else: ?>
											<span class="xn-clock disable"></span>
										<?php endif ?>
										
										<a href="#" class="xn-doc-text" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
										<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
				                            <div class="modal-dialog modal-sm" role="document">
				                                <div class="modal-content">
				                                    <div class="modal-header bg-darkgrey white">
				                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				                                        <h4 class="modal-title" id="gridSystemModalLabel">Add Category</h4>
				                                    </div>
				                                    <div class="modal-body">
				                                        <input type="hidden" name="class" value="blog">
				                                        <div class="form-group">
				                                            <label for="exampleInputEmail1">Category Name</label>
				                                            <input type="text" class="form-control" placeholder="Category" name="title">
				                                        </div>
				                                        <div class="form-group">
				                                            <label for="exampleInputPassword1">Parent</label>
				                                        </div>
				                                    </div>
				                                    <div class="modal-footer">
				                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				                                        <input type="submit" class="btn btn-primary" value="Save">
				                                    </div>
				                                </div>
				                            </div>
				                        </div>

										<?php if (empty($user['time'])): ?>
											<span id="btnAlpha-<?php echo $user['$id'] ?>" style="color: red; cursor: pointer;" class="xn-cancel-circled" onclick="alpha('<?php echo $user['$id'] ?>')"></span>
										<?php else: ?>
											<span class="xn-cancel-circled disable"></span>
										<?php endif ?>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>	
				</div>
			</div>
		</paper-detail>
	</div>

	<!-- The Modal -->
	<div id="myModal" class="modal">

	  <!-- Modal content -->
	  <div class="modal-content">
	    <span class="close">&times;</span>
	    <p>Some text in the Modal..</p>
	  </div>

	</div>

	<script>
		// Get the modal
		var modal = document.getElementById('myModal');

		// Get the button that opens the modal
		var btn = document.getElementById("myBtn");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks the button, open the modal 
		btn.onclick = function() {
		    modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		    modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		    if (event.target == modal) {
		        modal.style.display = "none";
		    }
		}

		function time(id) {
	        var d = new Date();
	        var time = (d.getHours()<10?'0':'') + d.getHours()+':'+(d.getMinutes()<10?'0':'') + d.getMinutes();

	        result = [];
	        result[0] = id;
	        result[1] = time;
	        result[2] = "<?php echo $dataEvent['$id'] ?>";
	        result[3] = "<?php echo $dataEvent['category'] ?>";

	        document.getElementById("btnTime-"+id).className += " disable";
	        document.getElementById("btnTime-"+id).removeAttribute("onclick");

	        document.getElementById("btnAlpha-"+id).className += " disable";
	        document.getElementById("btnAlpha-"+id).removeAttribute("onclick");

	        $.ajax({
	            url : "<?php echo URL::site('attendance/null/create') ?>",
	            type : "POST",
	            data : {result, result},
	            success : function (respon)
	                {
	                	var respon = $.parseJSON(respon);
        				console.log(respon.name);

	                    document.getElementById("time-"+id).innerHTML = time;
	                    document.getElementById("time-"+id).style.backgroundColor = respon.color;

	                    document.getElementById("status-"+id).innerHTML = respon.name;
	                    document.getElementById("status-"+id).style.backgroundColor = respon.color;
	                },
	        });
	    }

	    function alpha(id) {
	        var d = new Date();
	        var time = "00:00";

	        result = [];
	        result[0] = id;
	        result[1] = time;
	        result[2] = "<?php echo $dataEvent['$id'] ?>";
	        result[3] = "<?php echo $dataEvent['category'] ?>";

	        document.getElementById("btnTime-"+id).className += " disable";
	        document.getElementById("btnTime-"+id).removeAttribute("onclick");

	        document.getElementById("btnAlpha-"+id).className += " disable";
	        document.getElementById("btnAlpha-"+id).removeAttribute("onclick");

	        $.ajax({
	            url : "<?php echo URL::site('attendance/null/create') ?>",
	            type : "POST",
	            data : {result, result},
	            success : function (respon)
	                {
	                	var respon = $.parseJSON(respon);
        				console.log(respon.name);

	                    document.getElementById("time-"+id).innerHTML = time;
	                    document.getElementById("time-"+id).style.backgroundColor = respon.color;

	                    document.getElementById("status-"+id).innerHTML = respon.name;
	                    document.getElementById("status-"+id).style.backgroundColor = respon.color;
	                },
	        });
	    }
	</script>
@endsection

@section('contextual')
@endsection