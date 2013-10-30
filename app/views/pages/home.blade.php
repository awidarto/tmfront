@extends('layout.front')

@section('content')
<div id="home">
    <div class="row">
        <div class="col-md-8">
            <div class="col-md-6">
                <h2>PRACTICAL DESIGN</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/5.jpg">
                    <h1>Sewing Table</h1>
                    <a href="">buy now for IDR 2.350.000</a>
                </div>
            </div>
            <div class="col-md-6">
                <h2>LIFESTYLE</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/4.jpg">
                    <h1>A1 B1 Soft Baskets</h1>
                    <a href="">buy now for IDR 300.000</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <h2>LOGIN / SIGN UP</h2>
            <p>

                Here Foo and Bar are POJOs that you will use within your app. Hongo library will automatically create sqlite tables for those classes, which will allow you to insert, query, update and delete data easily:

                In order to interact with the database, you must get an implementation of the SqlAdapter interface. You can do so this way
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h2>INTERIOR DESIGN</h2>
            <img src="{{ URL::to('/') }}/images/dummy/6.jpg">
        </div>
        <div class="col-md-4">
            <h2>WHAT'S HAPPENING</h2>
            <p>
                Here Foo and Bar are POJOs that you will use within your app. Hongo library will automatically create sqlite tables for those classes, which will allow you to insert, query, update and delete data easily:

                In order to interact with the database, you must get an implementation of the SqlAdapter interface. You can do so this way
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="col-md-6">
                <h2>COMFORT ABOVE ALL</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/1.jpg">
                    <h1>Vespa Throw Pillows</h1>
                    <a href="">buy now for IDR 250.000</a>
                </div>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/2.jpg">
                    <h1>Book Stacks Throw Pillows</h1>
                    <a href="">buy now for IDR 350.000</a>
                </div>
            </div>
            <div class="col-md-6">
                <h2>FUNCTIONAL & UNIQUE</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/3.jpg">
                    <h1>Elephant Shape Bookshelf</h1>
                    <a href="">buy now for IDR 450.000</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <h2>@toimoitwit</h2>
            <p>
                Here Foo and Bar are POJOs that you will use within your app. Hongo library will automatically create sqlite tables for those classes, which will allow you to insert, query, update and delete data easily:

                In order to interact with the database, you must get an implementation of the SqlAdapter interface. You can do so this way
            </p>
        </div>
    </div>
</div>
@stop