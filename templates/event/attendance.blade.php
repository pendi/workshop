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
	    width: 50%;
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

	#parent {
        height: 350px;
    }
    
    #fixTable {
        width: 100%;
    }
	</style>

	<script>
        $(document).ready(function() {
            $("#fixTable").tableHeadFixer({"left" : 1}); 
        });
    </script>

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
				<div class="table-container" id="parent">
					<table id="fixTable" class="nowrap">
						<thead>
							<tr>
								<th>Name</th>
								<th>Time</th>
								<th>Status</th>
								<th>Description</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($dataUser as $user): ?>
								<tr>
									<td><?php echo $user['first_name'].' '.$user['last_name'] ?></td>
									<td id="time-<?php echo $user['$id'] ?>" <?php echo !empty($user['time']) ? 'style="background-color: ' .$user['status_color']. ';"' : '' ?>><?php echo $user['time'] ?></td>
									<td id="status-<?php echo $user['$id'] ?>" <?php echo !empty($user['time']) ? 'style="background-color: ' .$user['status_color']. ';"' : '' ?>><?php echo $user->format('status') ?></td>
									<td id="description-<?php echo $user['$id'] ?>" <?php echo !empty($user['time']) ? 'style="background-color: ' .$user['status_color']. ';"' : '' ?>><?php echo $user['description'] ?></td>
									<td>
										<?php if (empty($user['time'])): ?>
											<span id="btnTime-<?php echo $user['$id'] ?>" style="color: green; cursor: pointer;" class="xn-clock" onclick="time('<?php echo $user['$id'] ?>')"></span>
										<?php else: ?>
											<span class="xn-clock disable"></span>
										<?php endif ?>
										
										<a href="#" class="xn-doc-text" onclick="myBtn('<?php echo $user['$id'] ?>')"></a>
										<!-- The Modal -->
										<div id="myModal-<?php echo $user['$id'] ?>" class="modal">

										 	<!-- Modal content -->
										 	<div class="modal-content">
										    	<span class="close" id="close-<?php echo $user['$id'] ?>">&times;</span>
										    	<?php
										    		$action = URL::site('attendance/null/createManual');
										    		if (isset($user['id_attendance']) && !empty($user['id_attendance'])) {
										    			$action = URL::site('attendance/'.$user['id_attendance'].'/update');
										    		}
										    	?>
										    	<form action="<?php echo $action ?>" method="POST" class="read">
										    		<?php if (isset($user['id_attendance']) && empty($user['id_attendance'])): ?>
										    			<input type="hidden" name="event" value="<?php echo $dataEvent['$id'] ?>">
										    			<input type="hidden" name="user" value="<?php echo $user['$id'] ?>">
										    			<input type="hidden" name="category" value="<?php echo $dataEvent['category'] ?>">
										    		<?php endif ?>
										    		<div class="row">
										    			<div class="span-12 medium-12">
										    				<div class="row">
										    					<label>Name</label>
										    					<input type="text" disabled="disabled" value="<?php echo $user['first_name'].' '.$user['last_name'] ?>">
										    				</div>
										    			</div>
										    			<div class="span-12 medium-12">
										    				<div class="row">
										    					<label>Status</label>
										    					<select name="status">
										    						<option value="1" <?php echo $user['status'] == '1' || $user['status'] == '2' ? 'selected' : '' ?> >Present</option>
										    						<option value="3" <?php echo $user['status'] == '3' ? 'selected' : '' ?> >Permit</option>
										    						<option value="4" <?php echo $user['status'] == '4' ? 'selected' : '' ?> >Alpha</option>
										    					</select>
										    				</div>
										    			</div>
										    			<div class="span-6 medium-12">
										    				<div class="row">
										    					<label>Time Hours</label>
										    					<?php $time_hour = explode(":", $user['time']) ?>
										    					<select name="hours">
										    						<?php for ($iHour=0; $iHour<=24; $iHour++) : ?>
										    							<?php $hours = str_pad($iHour, 2, 0, STR_PAD_LEFT) ?>
										    							<option value="<?php echo $hours ?>" <?php echo isset($time_hour[0]) && $time_hour[0] == $hours ? 'selected' : '' ?> ><?php echo $hours ?></option>
										    						<?php endfor ?>
										    					</select>
										    				</div>
										    			</div>
										    			<div class="span-6 medium-12">
										    				<div class="row">
										    					<label>Time Minutes</label>
										    					<?php $time_minutes = explode(":", $user['time']) ?>
										    					<select name="minutes">
										    						<?php for ($iMinutes=0; $iMinutes<=59; $iMinutes++) : ?>
										    							<?php $minutes = str_pad($iMinutes, 2, 0, STR_PAD_LEFT) ?>
										    							<option value="<?php echo $minutes ?>" <?php echo isset($time_minutes[1]) && $time_minutes[1] == $minutes ? 'selected' : '' ?> ><?php echo $minutes ?></option>
										    						<?php endfor ?>
										    					</select>
										    				</div>
										    			</div>
										    			<div class="span-12 medium-12">
										    				<div class="row">
										    					<label>Description</label>
										    					<textarea name="description" cols="30" rows="10"><?php echo $user['description'] ?></textarea>
										    				</div>
										    			</div>
										    			<div class="span-6 medium-12">
										    				<div class="row">
										    					<input type="submit">
										    				</div>
										    			</div>
										    		</div>
										    	</form>
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

	<script>
		function myBtn(id) {
			// Get the modal
			var modal = document.getElementById('myModal-'+id);

			// Get the button that opens the modal
			var btn = document.getElementById("myBtn-"+id);

			// Get the <span> element that closes the modal
			var span = document.getElementById("close-"+id);

			// When the user clicks the button, open the modal 
			modal.style.display = "block";

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
	        document.getElementById("btnTime-"+id).removeAttribute("style");

	        document.getElementById("btnAlpha-"+id).className += " disable";
	        document.getElementById("btnAlpha-"+id).removeAttribute("onclick");
	        document.getElementById("btnAlpha-"+id).removeAttribute("style");

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

	                    document.getElementById("description-"+id).style.backgroundColor = respon.color;
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
	        document.getElementById("btnTime-"+id).removeAttribute("style");

	        document.getElementById("btnAlpha-"+id).className += " disable";
	        document.getElementById("btnAlpha-"+id).removeAttribute("onclick");
	        document.getElementById("btnAlpha-"+id).removeAttribute("style");

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

	                    document.getElementById("description-"+id).style.backgroundColor = respon.color;
	                },
	        });
	    }
	</script>
@endsection

@section('contextual')
@endsection