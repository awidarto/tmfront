@extends('layout.front')

@section('content')
<style type="text/css">
    label{
        display: block;
        border: none;
    }

    button#submit{
        text-transform: uppercase;
        color: #FFF;
        font-size: 12px;
    }

</style>
<div id="home">
    <div class="row">
        <div class="col-md-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            @if($result == true)
                <h2>Transfer Confirmation Success</h2>
                <div class="container" style="display:block;font-size:12px;">
                    <p>
                        Thank you for your payment confirmation, we will verify and proceed with shipment
                    </p>
                    <p>
                        or call us and use this the transaction code.
                        Have a good day !
                    </p>
                </div>

            @else
                <h2>Transfer Confirmation Failed</h2>
                <div class="container" style="display:block;font-size:12px;">
                    <p>
                        Unfortunately we can process your confirmation. But no worry, just call us and we'll verify your payment in a jiffy.
                        Have a good day !
                    </p>
                </div>
            @endif
        </div>
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@stop