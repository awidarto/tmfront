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
    {{ HTML::style('font-awesome/css/font-awesome.min.css') }}

    <!-- Custom styles for this template -->
    {{ HTML::style('css/typography.css') }}
    {{ HTML::style('css/sticky-footer-navbar.css') }}

    {{ HTML::style('js/bxslider/jquery.bxslider.css')}}
    {{ HTML::style('css/lionbars.css')}}

    {{ HTML::style('css/app.css') }}
    {{ HTML::style('css/bootstrap-magnify.min.css') }}
    {{ HTML::style('css/multizoom.css') }}


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="../../assets/js/html5shiv.js"></script>
        <script src="../../assets/js/respond.min.js"></script>
        <![endif]-->

    {{ HTML::script('js/jquery-1.9.1.js')}}
    {{ HTML::script('js/jquery-ui-1.9.2.custom.min.js')}}

    {{ HTML::script('js/bxslider/jquery.bxslider.min.js')}}

    {{ HTML::script('js/jquery.lionbars.0.3.min.js')}}

    {{ HTML::script('js/bootstrap-magnify.min.js')}}

    {{ HTML::script('js/zoomsl-3.0.min.js') }}

    {{ HTML::script('js/gmap3/gmap3.js') }}

    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=en" />

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
                    <a href="{{ URL::to('/') }}"><img class="img-responsive" src="{{ URL::to('images/').'/tmlogotrans.png' }}"></a>
                </div>
                <div class="col-lg-4" id="tm-side-head">
                    @if(isset($tmhead) && is_array($tmhead) && !empty($tmhead))
                        {{ $tmhead[0]['body'] }}
                    @endif
                </div>
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