<?php
    function sa($item){
        if(URL::to($item) == URL::full()){
            return  'class="active"';
        }else{
            return '';
        }
    }
?>

<ul class="nav navbar-nav">
    <li {{ sa('collection/interior') }}  ><a href="{{ URL::to('collection/interior') }}" >Interior</a></li>
    <li {{ sa('collection/lifestyle') }} ><a href="{{ URL::to('collection/lifestyle') }}" >Lifestyle</a></li>
    <li {{ sa('collection/art') }} ><a href="{{ URL::to('collection/art') }}" >Art</a></li>

    <li {{ sa('contact') }} ><a href="{{ URL::to('contact') }}"  >Contact Us</a></li>
    <li {{ sa('news') }} ><a href="{{ URL::to('news') }}" >News</a></li>
    <li {{ sa('about') }} ><a href="{{ URL::to('about') }}" >About Us</a></li>
    <li {{ sa('/') }} ><a href="{{ URL::to('/') }}" >Home</a></li>

</ul>