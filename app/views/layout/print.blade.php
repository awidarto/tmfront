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


    {{ HTML::style('css/app.css') }}
    {{ HTML::style('css/bootstrap-magnify.min.css') }}

    <style type="text/css">
        #wrap{
            padding: 10px;
        }
    </style>

</head>

<body>

    <style type="text/css">
        h6{
            display: block !important;
        }

        p{
            font-size: 12px;
        }
    </style>

    <!-- Wrap all page content here -->
    <div id="wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="tm-logo-container">
                    <a href="{{ URL::to('/') }}"><img class="img-responsive" style="width:250px;height:auto;" src="{{ URL::to('images/').'/logo_toimoi_color.png' }}"></a>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    @include('partials.address')
                </div>
            </div>
        </div>
        <!-- Begin page content -->
        <div class="container" style="padding-top:25px;">
            @yield('content')

            <div class="row" style="margin-top:20px;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if($payment['payment_method'] == 'transfer')

                        <p>
                            Thank you for your purchase, this is your Transaction Code :
                        </p>
                            <p style="font-size:18pt;font-weight:bold;display:block;text-align:center;padding:8px;">{{ $payment['sessionId'] }}</p>
                        <p>
                            please keep it handy, you will need it to confirm your payment later.<br />
                            If you have made your transfer payment , you may confirm your payment using our online payment confirmation or call us and use this the transaction code.<br />
                            Have a good day !
                        </p>

                    @else

                    @endif
                </div>
            </div>

        </div>
    </div>


</body>
</html>