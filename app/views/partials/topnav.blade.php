
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
            <li {{ sa('/') }} ><a href="{{ URL::to('/') }}" >Home</a></li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Webshop <b class="caret"></b></a>
              <ul class="dropdown-menu multi-level">
                <?php
                  /*
                  $categories = Prefs::getProductCategory()->productCatToSelection('slug', 'title', false );
                  @foreach($categories as $category=>$cname)
                  <li {{ sa('shop/collection' ) }} ><a href="{{ URL::to('shop/collection/'.$category) }}" >{{ $cname }}</a></li>
                  @endforeach
                  */
                ?>
               <li class="dropdown-submenu" >
                  <a href="{{ URL::to('shop/collection/home-living') }}" >Home Living</a>
                  <ul class="dropdown-menu">
                    <li {{ sa('shop/collection' ) }} ><a href="{{ URL::to('shop/collection/accesories') }}" >Accesories</a></li>
                    <li {{ sa('shop/collection' ) }} ><a href="{{ URL::to('shop/collection/cooking-dining') }}" >Cooking & Dining</a></li>
                    <li {{ sa('shop/collection' ) }} ><a href="{{ URL::to('shop/collection/furniture') }}" >Furniture</a></li>
                    <li {{ sa('shop/collection' ) }} ><a href="{{ URL::to('shop/collection/lighting') }}" >Lighting</a></li>
                  </ul>
               </li>
               <li class="dropdown-submenu" >
                  <a href="{{ URL::to('shop/collection/lifestyle') }}" >Lifestyle</a>
                  <ul class="dropdown-menu">
                    <li {{ sa('shop/collection' ) }} ><a href="{{ URL::to('shop/collection/bags') }}" >Bags</a></li>
                    <li {{ sa('shop/collection' ) }} ><a href="{{ URL::to('shop/collection/stationary') }}" >Stationary</a></li>
                    <li {{ sa('shop/collection' ) }} ><a href="{{ URL::to('shop/collection/gifts') }}" >Gifts</a></li>
                  </ul>
               </li>

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

      <!-- Static navbar -->
