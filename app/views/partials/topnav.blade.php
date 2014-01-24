<?php
    function sa($item){
        if(URL::to($item) == URL::full()){
            return  'class="active"';
        }else{
            return '';
        }
    }
?>

      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li {{ sa('/') }} ><a href="{{ URL::to('/') }}" >Home</a></li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Webshop <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li {{ sa('shop/collection/art') }} ><a href="{{ URL::to('shop/collection/art') }}" >Art</a></li>
                <li {{ sa('shop/collection/furniture') }}  ><a href="{{ URL::to('shop/collection/furniture') }}" >Furniture</a></li>
                <li {{ sa('shop/collection/home-accessories') }}  ><a href="{{ URL::to('shop/collection/home-accessories') }}" >Home Accessories</a></li>
                <li {{ sa('shop/collection/lifestyle') }} ><a href="{{ URL::to('shop/collection/lifestyle') }}" >Lifestyle</a></li>
                <li {{ sa('shop/collection/gifts') }}  ><a href="{{ URL::to('shop/collection/gifts') }}" >Gifts</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Projects <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li {{ sa('projects/interior') }}  ><a href="{{ URL::to('projects/interior') }}" >Interior</a></li>
                <li {{ sa('projects/others') }} ><a href="{{ URL::to('projects/others') }}" >Event</a></li>
              </ul>
            </li>


            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">News <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li {{ sa('news/product-story') }}  ><a href="{{ URL::to('news/product-story') }}" >Product's Story</a></li>
                <li {{ sa('news/event') }} ><a href="{{ URL::to('news/event') }}" >Event</a></li>
              </ul>
            </li>

            <li {{ sa('press') }} ><a href="{{ URL::to('press') }}" >Press</a></li>
            <li {{ sa('about') }} ><a href="{{ URL::to('about') }}" >About Us</a></li>
            <li {{ sa('contact') }} ><a href="{{ URL::to('contact') }}"  >Find Us</a></li>

          </ul>

          <ul class="nav navbar-nav" id="tm-socmed">
            <li><a class="social" href="../navbar-static-top/"><img src="{{ URL::to('/')}}/images/twitter.png"></a></li>
            <li><a class="social" href="../navbar-static-top/"><img src="{{ URL::to('/')}}/images/facebook.png"></a></li>
            <li><a class="social" href="../navbar-static-top/"><img src="{{ URL::to('/')}}/images/instagram.png"></a></li>
            <li><a class="social" href="../navbar-static-top/"><img src="{{ URL::to('/')}}/images/pinterest.png"></a></li>
            <li><a class="social" href="../navbar-static-top/"><img src="{{ URL::to('/')}}/images/gplus.png"></a></li>
          </ul>

        </div><!--/.nav-collapse -->
