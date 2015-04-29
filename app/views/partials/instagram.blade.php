<h2>@TOIMOIGRAM</h2>
<style type="text/css">

    #instabox img{
        width:200px;
        height:auto;
    }

    ul#instaslider li{
        float: left;
        padding: 0px;
    }
</style>
<div id="instabox" >
    <span id="insta-loader" style="display:none;">Loading instagram feed...</span>
    <ul id="instaslider" class="slider">
    </ul>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#insta-loader').show();
        $.get(
            '{{ URL::to('ajax/instagram')}}',
            function(data){
                $('#insta-loader').hide();
                var ul = $('#instaslider');
                for( i = 0;i < data.image.length;i++ ){
                    ul.append('<li><img class="insta-image" src="' + data.image[i] + '" /></li>')
                }
                //$('img#insta-image').attr('src', data.image);
                $('.slider').bxSlider({
                    mode: 'fade',
                    auto: true,
                    pager:false,
                    controls:false,
                    autoControls: false,
                    pause: 5000
                });

            },'json'
        );
    });

</script>
