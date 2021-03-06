<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>
			@section('pagetitle')
				{{ f('page.title', 'Dashboard') }}
			@show
			- Bono
		</title>
		<meta name="description" content="{{ f('page.title', 'Great App') }}">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="<?php echo Theme::base('css/app.min.css') ?>" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo Theme::base('vendor/bootstrap/dist/css/bootstrap.min.css')?>">
		<link href="<?php echo Theme::base('css/style.css') ?>" rel="stylesheet">

		<!-- favicon -->
		<link rel="shortcut icon" href="<?php echo Theme::base('img/event-favicon/favicon.ico') ?>" type="image/x-icon">
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo Theme::base('img/event-favicon/apple-icon-57x57.png') ?>">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php echo Theme::base('img/event-favicon/apple-icon-60x60.png') ?>">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo Theme::base('img/event-favicon/apple-icon-72x72.png') ?>">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo Theme::base('img/event-favicon/apple-icon-76x76.png') ?>">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo Theme::base('img/event-favicon/apple-icon-114x114.png') ?>">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo Theme::base('img/event-favicon/apple-icon-120x120.png') ?>">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo Theme::base('img/event-favicon/apple-icon-144x144.png') ?>">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo Theme::base('img/event-favicon/apple-icon-152x152.png') ?>">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo Theme::base('img/event-favicon/apple-icon-180x180.png') ?>">
		<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo Theme::base('img/event-favicon/android-icon-192x192.png') ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo Theme::base('img/event-favicon/favicon-32x32.png') ?>">
		<link rel="icon" type="image/png" sizes="96x96" href="<?php echo Theme::base('img/event-favicon/favicon-96x96.png') ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo Theme::base('img/event-favicon/favicon-16x16.png') ?>">
		<link rel="manifest" href="<?php echo Theme::base('img/event-favicon/manifest.json') ?>">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="<?php echo Theme::base('img/event-favicon/ms-icon-144x144.png') ?>">
		<meta name="theme-color" content="#ffffff">

		@section('customcss')
			<!-- Custom CSS -->
			<link href="<?php echo Theme::base('css/select2.min.css') ?>" rel="stylesheet">
		@show

		<script src="<?php echo Theme::base('vendor/jquery/dist/jquery.min.js')?>"></script>
		<script type="text/javascript" src="<?php echo Theme::base('js/app.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo Theme::base('js/custom.js') ?>"></script>
		<script src="<?php echo Theme::base('vendor/bootstrap/dist/js/bootstrap.min.js')?>"></script>

		@section('customjs')
			<!-- Custom JS -->
			<script type="text/javascript" src="<?php echo Theme::base('js/fixed/tableHeadFixer.js') ?>"></script>
			<script type="text/javascript" src="<?php echo Theme::base('js/select2.min.js') ?>"></script>
		@show

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="<?php echo Theme::base('js/app.ie.min.js') ?>"></script>
		<![endif]-->
	</head>
	<body class="@section('has-sidebar')
			has-sidebar
		@show">
		@section('skeleton')
			<!--[if lt IE 7]>
			@section('iewarning')
				<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
			@show
			<![endif]-->

			@section('notification')
				{{ f('notification.show') }}
			@show

			<?php
				if (isset($_SESSION['user'])) {
					$user = \Norm::factory("User")->findOne($_SESSION['user']['$id']);
				}
			?>

			@section('topbar')
				<nav class="nav-menu">
					<div class="pull-left">
						<h1>
							<a href="<?php echo URL::base() ?>">
								@section('applogo')
									<span class="logo">
										@section('applogo.image')
											<img src="<?php echo Theme::base('img/event_logo.png') ?>" alt="Bono" />
										@show
									</span>
									<span>
										@section('applogo.title')
											Event
										@show
									</span>
								@show
							</a>
						</h1>
					</div>
					@section('actions')
						<nav class="actions">
							@section('back')
							@show
							<div class="clear"></div>
						</nav>
					@show
					<div class="pull-right">
						@section('usermenu')
							<ul class="topbar">
								<li class="sub user">
									<a href="#">
										<?php if (isset($_SESSION['user'])): ?>
											<!-- <span class="photo" style="background-image: url('http://lorempixel.com/200/200/people');"></span> -->
											<span><?php echo $user['first_name'].' '.$user['last_name'] ?></span>
											<i class="xn xn-down-open-mini"></i>
										<?php endif ?>
									</a>
									<ul class="context-menu higher right" style="padding: 0px">
										<li><a href="<?php echo URL::site('logout')?>">LogOut</a></li>	
									</ul>
								</li>
							</ul>
						@show
					</div>
				</nav>
			@show

			@section('sidebar')
				<aside class="sidebar">
					<ul class="nav with-icon">
						<li><a href="<?php echo URL::site() ?>"><i class="icn-left xn xn-home"></i> Dashboard <i class="icn-right xn xn-right-open-mini"></i></a></li>
						<li class="devider"></li>
						@if(f('auth.allowed', '/event'))
						<li><a href="<?php echo URL::site('event') ?>"><i class="icn-left xn xn-check"></i> Event <i class="icn-right xn xn-right-open-mini"></i></a></li>
						@endif
						@if(f('auth.allowed', '/report'))
						<li><a href="<?php echo URL::site('report') ?>"><i class="icn-left xn xn-chart-area"></i> Report <i class="icn-right xn xn-right-open-mini"></i></a></li>
						@endif

						<li class="devider"></li>
						@if(f('auth.allowed', '/category'))
						<li><a href="<?php echo URL::site('category') ?>"><i class="icn-left xn xn-list-add"></i> Category <i class="icn-right xn xn-right-open-mini"></i></a></li>
						@endif
						@if(f('auth.allowed', '/statuses'))
						<li><a href="<?php echo URL::site('statuses') ?>"><i class="icn-left xn xn-list-add"></i> Status <i class="icn-right xn xn-right-open-mini"></i></a></li>
						@endif
						@if(f('auth.allowed', '/rules'))
						<li><a href="<?php echo URL::site('rules') ?>"><i class="icn-left xn xn-list-add"></i> Rules <i class="icn-right xn xn-right-open-mini"></i></a></li>
						@endif

						<li class="devider"></li>
						@if(f('auth.allowed', '/user'))
						<li><a href="<?php echo URL::site('user') ?>"><i class="icn-left xn xn-users"></i> User View <i class="icn-right xn xn-right-open-mini"></i></a></li>
						@endif
						@if(f('auth.allowed', '/role'))
						<li><a href="<?php echo URL::site('role') ?>"><i class="icn-left xn xn-vcard"></i> Role View <i class="icn-right xn xn-right-open-mini"></i></a></li>
						@endif
						@if(f('auth.allowed', '/previleges'))
						<li><a href="<?php echo URL::site('previleges') ?>"><i class="icn-left xn xn-vcard"></i> Previleges View <i class="icn-right xn xn-right-open-mini"></i></a></li>
						@endif
					</ul>
				</aside>
			@show

			@section('page')
				<main class="content @section('main.classes')
						has-contextual
					@show">

					@section('content')
						<div class="wrapper">
							@section('fields')
								&nbsp;
							@show

							@section('select2')
								<script type="text/javascript">
							        $(document).ready(function() {
							        	$(".select-2").select2();
							        });
							    </script>
							@show
						</div>
					@show

					@section('contextual')
						<nav id="contextual">
							@section('contextual.content')
								&nbsp;
							@show
						</nav>
					@show
				</main>
			@show
		@show

		@section('templatemodal')
    	@show

		<div id="deletemodal" class="modal fade" tabindex="-1" role="dialog">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                    <h4 class="modal-title">Modal title</h4>
	                </div>
	                <div class="modal-body">
	                    <p>One fine body&hellip;</p>
	                </div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                    <button type="button" class="btn btn-primary">Save changes</button>
	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	</body>
</html>