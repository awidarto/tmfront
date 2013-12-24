<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>{{ Config::get('site.name') }}</title>

    <!-- Bootstrap core CSS -->
    {{ HTML::style('bootstrap/css/bootstrap.min.css') }}
    {{ HTML::style('bootstrap/css/font-awesome.min.css') }}

    <!-- Custom styles for this template -->
    {{ HTML::style('css/typography.css') }}
    {{ HTML::style('css/sticky-footer-navbar.css') }}
    {{ HTML::style('css/app.css') }}


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="../../assets/js/html5shiv.js"></script>
        <script src="../../assets/js/respond.min.js"></script>
        <![endif]-->

    {{ HTML::script('js/jquery-1.9.1.js')}}
    {{ HTML::script('js/jquery-ui-1.9.2.custom.min.js')}}

    {{ HTML::script('js/bxslider/jquery.bxslider.min.js')}}

    {{ HTML::script('js/jquery.lionbars.0.3.min.js')}}


    {{ HTML::style('js/bxslider/jquery.bxslider.css')}}
    {{ HTML::style('css/lionbars.css')}}

    <script type="text/javascript">
        $(document).ready(function(){
            $('.lionbar').lionbars();
        });
    </script>

</head>

<body>

    <!-- Wrap all page content here -->
    <div id="wrap">
        <!-- topmost header -->
        <div id="tm-head" class="visible-md visible-lg">
            <div class="container">
                <div class="col-lg-8" id="tm-logo-container">
                    <a href="{{ URL::to('/') }}"><img class="img-responsive" src="{{ URL::to('images/').'/tmlogo_med.png' }}"></a>
                </div>
                <div class="col-lg-4" id="tm-side-head">
                    <p><a href="#">Pin a fixed-height footer to the bottom of the viewport in desktop browsers with this custom HTML and CSS. A fixed navbar has been added within</a></p>
                </div>
            </div>
        </div>
        <!-- Fixed navbar -->
        <div class="navbar navbar-default "  id="tm-head-navbar">
            <div class="container" >
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand visible-sm visible-xs" href="{{ URL::to('/')}}">{{ Config::get('site.name') }}</a>
                </div>

                <div class="collapse navbar-collapse">
                    @include('partials.topnav')
                </div><!--/.nav-collapse -->
            </div>
        </div>

        <!-- Begin page content -->
        <div class="container">
            @yield('content')
        </div>
    </div>

</div>

<div id="footer">
    <div class="container">
        <p class="text-muted credit">SITE MAP | TERMS &amp; CONDITIONS | PRIVACY POLICY
           <span class="pull-right"> &copy; {{ date('Y',time()) }} {{ Config::get('site.design')}}</span>
        </p>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
{{ HTML::script('bootstrap/js/bootstrap.min.js')}}

</body>
</html>