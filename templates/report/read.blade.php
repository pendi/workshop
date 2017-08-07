@extends('shared/read')

@section('pagetitle')
    Report
@stop

@section('back')
    <ul class="flat left">
        <li><a href="{{ f('controller.url') }}"><i class="xn-left-open"></i>{{ l('Back') }}</a></li>
        <li><a href="{{ f('controller.url', '/'.$entry['$id'].'?export=1') }}"><i class="xn-export"></i> {{ l('Export') }}</a></li>
    </ul>
@stop

@section('fields')
    <style>
        #parent {
            max-height: 470px;
        }
        
        #fixTable {
            width: 100%;
        }
    </style>

    <h3>Report <?php echo $entry['name'] ?></h3>
    <div class="read">
    	<div class="table-container" id="parent">
            <table id="fixTable" class="table nowrap hover">
                <thead>
                    <tr>
                    	<th>Event</th>
                    	<?php foreach ($dataUsers as $key => $user): ?>
                    		<th><?php echo $user['first_name'].' '.$user['last_name'] ?></th>
                    	<?php endforeach ?>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach ($dataEvents as $key => $event): ?>
                    	<tr>
                    		<td><b>(<?php echo date("d M Y", strtotime($event['date'])) ?>)</b> <?php echo $event['name'] ?></td>
                    		<?php foreach ($event['attendance'] as $att => $attendance): ?>
                    			<td style="border-right: 1px solid rgb(221, 221, 221); background-color: <?php echo $attendance['status_color'] ?>"><span data-toggle="tooltip" title="<?php echo $attendance['tooltip'] ?>"><?php echo $attendance['time'] ?></span></td>
                    		<?php endforeach ?>
                    	</tr>
                	<?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#fixTable").tableHeadFixer({"left" : 1}); 
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop

@section('contextual.content')
@stop