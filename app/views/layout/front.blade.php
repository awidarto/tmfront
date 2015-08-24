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

    {{ HTML::style('css/flick/jquery-ui-1.9.2.custom.min.css')}}

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

    {{ HTML::script('js/accounting.min.js') }}

    @if(isset($isloc) && $isloc == 1)
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=en" />
    @endif

    <script type="text/javascript">
        $(document).ready(function(){
            $('.lionbar').lionbars();
        });
    </script>

    <style type="text/css">
         a.cart-icon {
            font-size: 24px !important;
            margin-left: 8px;
            margin-top: 8px;
         }

         a.cart-icon:hover{
            text-decoration: none;
            color: maroon;
         }

        div.container.topbar{
            padding-top: 0px !important;
            text-align: right;
        }

        .spacer{
            display: inline-block;
            width: 25px;
        }

        .breadcrumb a{
            text-transform: uppercase;
            font-size: 12px;
        }

    </style>

</head>

<body>

    <!-- Wrap all page content here -->
    <div id="wrap">
        <!-- topmost header -->
        <div id="tm-head" class="visible-md visible-lg visible-sm visible-xs">
            {{--
            @if($head = Prefs::getHeader())
                {{ $head }}
            @else

            --}}
                <div class="container">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" id="tm-logo-container">
                        <a href="{{ URL::to('/') }}"><img class="img-responsive" src="{{ URL::to('images/').'/logo_toimoi_color.png' }}"></a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6" >
                        {{ Form::open(array('url' => 'search/collection','class'=>'form-inline', 'method'=>'get' ,'role'=>'form')) }}
                            <div class="form-group">
                                {{ Former::text('search','')->placeholder('Search')->style('width:330px;')->id('search')->class('search form-control') }}
                            </div>
                            <button type="submit" class="btn btn-default ">Search</button>
                        {{ Form::close() }}
                    </div>
                @if(Auth::check())
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display:block;padding-top:42px;">
                            <div>
                                <p>Welcome {{ Auth::user()->firstname.' '.Auth::user()->lastname }}</p>
                                <p><a style="padding-top:6px;display:form-inline"  href="{{ URL::to('logout') }}"  class="btn btn-primary" ><i class="fa fa-sign-out"></i> Logout</a></p>
                            </div>
                @else
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display:block;padding-top:68px;">
                            <a style="padding-top:6px;display:form-inline" href="{{ URL::to('login')}}" class="btn btn-primary" ><i class="fa fa-sign-in"></i> Login</a>
                @endif
                    <a href="{{URL::to('signup') }}">No account ? Sign Up !</a>
                    </div>
                    {{--

                            <div>
                                <p>You have <span id="cart-qty" >{{ Commerce::getCartItemCount(Auth::user()->activeCart,Config::get('site.outlet_id') )}}</span> items in your shopping cart<br />
                                    <a class="pull-left" href="{{ URL::to('shop/purchases')}}"><i class="fa fa-money"></i> My Purchases</a>
                                    <a class="pull-right" href="{{ URL::to('shop/cart')}}"><i class="fa fa-shopping-cart"></i> View Cart</a>
                                </p>
                            </div>

                    <div class="col-lg-4" id="tm-side-head">
                        @if(isset($tmhead) && is_array($tmhead) && !empty($tmhead))
                            {{ $tmhead[0]['body'] }}
                        @endif
                    </div>
                    --}}
                </div>
            {{--
            @endif
            --}}
        </div>
        <!-- Fixed navbar -->
        <div class="navbar navbar-default"  id="tm-head-navbar">
            <div class="container" style="border-bottom: thin solid #CCC" >
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse">
                    @include('partials.topnav')
                </div><!--/.nav-collapse -->

            </div>
        </div>
        <div class="container topbar">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pull-right">

                    <?php
                      if(isset($product) && isset($product['itemDescription'])){
                        $stext = Config::get('site.name').' - '.$product['itemDescription'];
                      }else{
                        $stext = Config::get('site.name');
                      }
                    ?>
                    <a class="social" href="https://twitter.com/share?text={{ urlencode( $stext ) }}&url={{ urlencode( URL::full() ) }}" target="_blank" ><img src="{{ URL::to('/')}}/images/twitter.png"></a>
                    <a class="social" href="http://www.facebook.com/sharer/sharer.php?u={{ urlencode( URL::full() ) }}&title={{ urlencode( $stext ) }}"><img src="{{ URL::to('/')}}/images/facebook.png" target="_blank" ></a>

                    <a class="social" href="http://pinterest.com/pin/create/bookmarklet/?url={{ urlencode( URL::full() ) }}&is_video=false&description={{ urlencode( $stext ) }}" target="_blank" ><img src="{{ URL::to('/')}}/images/pinterest.png"></a>

                    <style>.ig-b- { display: inline-block; }
                    .ig-b- img { visibility: hidden; }
                    .ig-b-:hover { background-position: 0 -60px; } .ig-b-:active { background-position: 0 -120px; }
                    .ig-b-24 { width: 24px; height: 24px; background: url(//badges.instagram.com/static/images/ig-badge-sprite-24.png) no-repeat 0 0; }
                    @media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
                    .ig-b-24 { background-image: url(//badges.instagram.com/static/images/ig-badge-sprite-24@2x.png); background-size: 60px 178px; } }</style>
                    <a href="http://instagram.com/toimoiindonesia?ref=badge" class="ig-b- ig-b-24"><img src="//badges.instagram.com/static/images/ig-badge-24.png" alt="Instagram" /></a>
                    {{--

                    <span class="ig-follow" data-id="e686823c1b" data-handle="toimoiindonesia" data-count="false" data-size="large" data-username="false"></span>


                    <script type="text/javascript">
                        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.src="//x.instagramfollowbutton.com/follow.js";s.parentNode.insertBefore(g,s);}(document,"script"));
                    </script>
                    --}}
                    <span class="spacer">&nbsp;</span>
                    <a class="cart-icon social" href="{{ URL::to('shop/purchases')}}"><i class="fa fa-money"></i></a>

                    <a class="cart-icon" href="{{ URL::to('shop/cart')}}"><i class="fa fa-shopping-cart"></i>
                    @if(Auth::check() && Commerce::getCartItemCount(Auth::user()->activeCart,Config::get('site.outlet_id')) > 0 )
                    <span id="cart-qty" style="vertical-align: top;" class="badge" >{{ Commerce::getCartItemCount(Auth::user()->activeCart,Config::get('site.outlet_id') )}}</span>
                    @endif
                    </a>

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

<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="eventModalLabel">Event Detail</h4>
      </div>
      <div class="modal-body" id="eventContent">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>