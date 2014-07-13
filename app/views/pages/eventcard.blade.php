<table id="event">
    <tr>
        <td colspan="2">
            <h3>{{ $ev['title']}}</h3>
        </td>
    </tr>
    <tr>
    @if(isset($ev['defaultpictures']['thumbnail_url']) && $ev['defaultpictures']['thumbnail_url'] != '')
        <td>
            <img class="event-picture" src="{{  $ev['defaultpictures']['thumbnail_url'] }}" alt="{{ $ev['title']}}" />
        </td>
        <td>
    @else
        <td colspan="2">
    @endif
            <p>{{ $ev['description']}}</p>
        </td>
    </tr>
    <tr>
        <td>
            From
        </td>
        <td>
            {{ date( 'D, d M Y' ,$ev['fromDate']->sec) }}</h3>
        </td>
    </tr>
    <tr>
        <td>
            Until
        </td>
        <td>
            {{ date( 'D, d M Y' ,$ev['toDate']->sec) }}</h3>
        </td>
    </tr>
</table>

<style type="text/css">
    table#event h3{
        padding-left: 0px;
        background-color: white;
        color:black;
        font-weight: bold;
    }
    .event-picture{
        margin: 4px;
    }
</style>