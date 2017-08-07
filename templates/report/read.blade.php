@extends('shared/read')

@section('pagetitle')
    Report
@stop

@section('back')
    <ul class="flat left">
        <li><a href="{{ f('controller.url') }}"><i class="xn xn-left-open"></i>{{ l('Back') }}</a></li>
        @if(f('auth.allowed', f('controller.uri', '/null/create')))
            <li><a href="{{ f('controller.url', '/null/create') }}"><i class="xn xn-plus"></i>{{ l('New') }}</a></li>
        @endif
        @if(f('auth.allowed', f('controller.uri', '/id/update')))
            <li><a href="{{ f('controller.url', '/:id/update') }}"><i class="xn xn-pencil"></i> {{ l('Edit') }}</a></li>
        @endif
    </ul>
@stop

@section('fields')
    <div class="read">
    	<div class="table-container">
            <table class="table nowrap hover">
                <thead>
                    <tr>
                    	<th>&nbsp;</th>
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
                				<?php //var_dump($attendance);exit(); ?>
                    			<td><?php echo $attendance['user'] ?></td>
                    		<?php endforeach ?>
                    	</tr>
                	<?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('contextual.content')
@stop