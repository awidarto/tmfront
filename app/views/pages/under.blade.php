@extends('layout.headonly')

@section('content')
<style type="text/css">
    #main h1{
        font-size: 24px;
        background-color: transparent;
        color:#222;
        padding-left: 0px;
    }

    #main h2{
        font-size: 18px;
        background-color: transparent;
        color:#222;
        padding-left: 0px;
    }

    #main p{
        font-size: 16px;
        color: #222;
    }

    #home .row{
        border: none;
    }
</style>
<div id="home">
    <div class="row">
        <div class="col-md-12" id="main">
                <h1>Hello</h1>
                <p>
                    We are still cleaning up the shop and doing lots and lots of housekeeping, well be right back for sure, so please keep checking in later.
                    <h2>Looking forward to see you again soon !</h2>
                </p>
        </div>
    </div>
</div>
@stop