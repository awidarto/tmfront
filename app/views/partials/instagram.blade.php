<h2>@TOIMOIGRAM</h2>
<style type="text/css">
    #instabox img{
        width:200px;
        height:auto;
    }
</style>
<div id="instabox" class="lionbar" >
    <span id="insta-loader" style="display:none;">Loading instagram feed...</span>
    <img id="insta-image" src="">
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#insta-loader').show();
        $.get(
            '{{ URL::to('ajax/instagram')}}',
            function(data){
                $('#insta-loader').hide();
                $('img#insta-image').attr('src', data.image);
            },'json'
        );
    });

</script>
