<!DOCTYPE html>
<html ng-app="tutapos">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>POS System</title>

    <link href="{{ asset('/admin/css/bootstrap.3.2.0.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/admin/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{ asset('/admin/css/ionicons/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- jvectormap -->
    <link href="{{ asset('/admin/css/jvectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="{{ asset('/admin/css/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="{{ asset('/admin/css/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="{{ asset('/admin/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset('/admin/css/style.css') }}" rel="stylesheet" type="text/css" />

 	

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	@yield('pagecss')
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
                    @if (Auth::check())
					    @if (Auth::user()->is_admin == 1)
                        <li><a href="{{ url('/') }}">{{trans('menu.dashboard')}}</a></li>
                        <li><a href="{{ url('/suppliers') }}">{{trans('menu.suppliers')}}</a></li>
                        <li><a href="{{ url('/po') }}">Purchase Orders</a></li>
                        
                        <li><a href="{{ url('/employees') }}">{{trans('menu.employees')}}</a></li>
					    @endif

                        <li><a href="{{ url('/customers') }}">{{trans('menu.customers')}}</a></li>
                        <li><a href="{{ url('/expenses') }}">Cost & Expenses</a></li>
						<li><a href="{{ url('/items') }}">Stock</a></li>
						
						<li><a href="{{ url('/sales') }}">{{trans('menu.sales')}}</a></li>
                        <li><a href="{{ url('/reports/sales') }}">{{trans('menu.sales_report')}}</a></li>
						<!--
                        <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('menu.reports')}} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/reports/receivings') }}">{{trans('menu.receivings_report')}}</a></li>
								<li><a href="{{ url('/reports/sales') }}">{{trans('menu.sales_report')}}</a></li>
							</ul>
						</li>
                        -->
						
					@endif
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
                                @if (Auth::user()->is_admin == 1)
								<li><a href="{{ url('/settings') }}">{{trans('menu.application_settings')}}</a></li>

								<li class="divider"></li>
                                <li><a href="{{ url('/itemcategories') }}">Item Categories</a></li>

                                <li class="divider"></li>
                                <li><a href="{{ url('/postsettings') }}">Post Settings</a></li>

                                <li class="divider"></li> 
                                <li><a href="{{ url('/bankaccounts') }}">Bank Accounts</a></li>

                                <li class="divider"></li> 
                                @endif
								<li><a href="{{ url('/auth/logout') }}">{{trans('menu.logout')}}</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
    <script src="{{ asset('/admin/js/jquery.2.1.1.min.js') }}"></script>
    <script src="{{ asset('/admin/js/bootstrap.3.2.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/admin/js/jquery-ui.1.11.1.min.js') }}" type="text/javascript"></script>
    
    <!-- Sparkline -->
    <script src="{{ asset('/admin/js/plugins/sparkline/jquery.sparkline.min.js') }}" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="{{ asset('/admin/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/admin/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('/admin/js/plugins/jqueryKnob/jquery.knob.js') }}" type="text/javascript"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('/admin/js/plugins/daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
    <!-- datepicker -->
    <script src="{{ asset('/admin/js/plugins/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('/admin/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ asset('/admin/js/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>


    <!-- AdminLTE App -->
    <!--<script src="{{ asset('/admin/js/AdminLTE/app.js') }}" type="text/javascript"></script> -->

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--<script src="{{ asset('/admin/js/AdminLTE/dashboard.js') }}" type="text/javascript"></script>-->

    @yield('pagejs')
    
</body>
</html>
