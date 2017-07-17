@extends('shared/search')

@section('back')
@stop

@section('content')
	<div class="wrapper">
		<h1 class="title">Attendance Category</h1>
		<div class="row">
			<namecardbook>
				@foreach ($entries as $entry)
					<namecard href="{{ f('controller.url', '/'.$entry['$id'].'/listAttendance') }}">
						<photo>{{ substr($entry->format('name','plain'),0,1) }}</photo>
						<name>{{$entry->format('name','plain')}}</name>
	                </namecard>
                @endforeach
			</namecardbook>
		</div>
	</div>
@stop