@extends('skeleton')

@section('back')
    <ul class="flat left">
        <li><a href="{{ f('controller.url', '/'.$dataEvent['category'].'/listEvent') }}"><i class="xn xn-left-open"></i>{{ l('Back') }}</a></li>
    </ul>
@stop

@section('content')
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
											<span id="btnTime-<?php echo $user['$id'] ?>" style="color: green; cursor: pointer;" class="xn-clock disable"></span>
										<?php endif ?>
										<span id="btnTime-<?php echo $user['$id'] ?>" style="color: red; cursor: pointer;" class="xn-cancel-circled" onclick="time('<?php echo $user['$id'] ?>')"></span>
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