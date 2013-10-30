<?php
    function sa($item){
        if(URL::to($item) == URL::full() ){
            return  'class="active"';
        }else{
            return '';
        }
    }
?>
<ul class="nav">
    @if(Auth::check())
        <li><a href="{{ URL::to('product') }}" {{ sa('product') }} >Media</a></li>
        <li><a href="{{ URL::to('playlist') }}" {{ sa('playlist') }} >Playlist</a></li>
    @endif
</ul>
