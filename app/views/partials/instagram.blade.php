<h2>@TOIMOIGRAM</h2>
<style type="text/css">
    #instabox img{
        width:200px;
        height:auto;
    }
</style>
<div id="instabox" class="lionbar" >
    <img id="insta-image" src="">
    </ul>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $.get(
            '{{ URL::to('ajax/instagram')}}',
            function(data){
                $('img#insta-image').attr('src', data.image);
            },'json'
        );
    });

</script>
