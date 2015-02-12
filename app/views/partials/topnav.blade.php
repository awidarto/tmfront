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
                <?php
                  $categories = Prefs::getProductCategory()->productCatToSelection('slug', 'title', false );
                ?>
                @foreach($categories as $category=>$cname)
                <li {{ sa('shop/collection' ) }} ><a href="{{ URL::to('shop/collection/'.$category) }}" >{{ $cname }}</a></li>
                @endforeach
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Projects <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li {{ sa('projects/interior') }}  ><a href="{{ URL::to('projects/interior') }}" >Interior</a></li>
                <li {{ sa('projects/others') }} ><a href="{{ URL::to('projects/others') }}" >Others</a></li>
              </ul>
            </li>


            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">News <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li {{ sa('page/list/news/product-story') }}  ><a href="{{ URL::to('page/list/news/products-story') }}" >Product's Story</a></li>
                <li {{ sa('event') }} ><a href="{{ URL::to('event') }}" >Event</a></li>
              </ul>
            </li>

            <li {{ sa('press') }} ><a href="{{ URL::to('press') }}" >Press</a></li>
            <li {{ sa('page/view/about') }} ><a href="{{ URL::to('page/view/about') }}" >About Us</a></li>
            <li {{ sa('location') }} ><a href="{{ URL::to('location') }}"  >Find Us</a></li>

          </ul>

          <ul class="nav navbar-nav" id="tm-socmed">
            <li>
              <a class="social" href="https://twitter.com/share?text={{ urlencode( Config::get('site.name') ) }}&url={{ urlencode( URL::full() ) }}" target="_blank" ><img src="{{ URL::to('/')}}/images/twitter.png"></a></li>
            <li><a class="social" href="http://www.facebook.com/share.php?u={{ urlencode( URL::full() ) }}&title={{ urlencode( Config::get('site.name') ) }}"><img src="{{ URL::to('/')}}/images/facebook.png"></a></li>
            <li><a class="social" href="../navbar-static-top/"><img src="{{ URL::to('/')}}/images/instagram.png"></a></li>
            <li><a class="social" href="http://pinterest.com/pin/create/bookmarklet/?url={{ urlencode( URL::full() ) }}&is_video=false&description={{ urlencode( Config::get('site.name') ) }}" ><img src="{{ URL::to('/')}}/images/pinterest.png"></a></li>
            {{--
            <li><a class="social" href="../navbar-static-top/"><img src="{{ URL::to('/')}}/images/gplus.png"></a></li>
            --}}
          </ul>

        </div><!--/.nav-collapse -->
