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

    <!-- Wrap all page content here -->
    <div id="wrap">
        <!-- Begin page content -->
        <div class="container">
            @yield('content')
        </div>
    </div>


</body>
</html>