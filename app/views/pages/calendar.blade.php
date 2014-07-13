@extends('layout.front')

@section('content')
<div id="home">
    <div class="row">
        <div class="col-md-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <h2>{{ $title }}</h2>
            <div id="calendar">

            </div>
        </div>
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>
</div>

{{ HTML::style('js/fullcalendar-2.0.0/fullcalendar.css')}}
{{ HTML::style('css/calendar.css')}}
{{ HTML::script('js/fullcalendar-2.0.0/lib/moment.min.js')}}
{{ HTML::script('js/fullcalendar-2.0.0/fullcalendar.js')}}

<style type="text/css">

.fc-state-highlight{
    background-color: #ddd;
}
.fc-event-title{
    padding-left: 4px;
}
/*
.fc-border-separate th, .fc-border-separate td {
    border-width: 2px 0 0 2px;
    border-color: #fff;
}

.fc th {
    background-color: #7da7d9;
}

.fc td {
    background-color: #00ffff;
}

table.fc-header td{
    background-color: #FFF;
}
*/
</style>
<script type="text/javascript">
    $(document).ready(function() {

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            eventSources:[
                {
                    url: '{{ URL::to('/') }}/event/data',
                    type: 'POST',
                    data: {
                        custom_param1: 'something',
                        custom_param2: 'somethingelse'
                    },
                    error: function() {
                        alert('there was an error while fetching events!');
                    }
                }
            ],
            eventClick: function(event) {

                $.get(
                    '{{ URL::to('event/detail')}}/' + event.id,
                    {},
                    function(data){
                        if(data.result == 'OK'){
                            $('#eventContent').html( data.html );
                            $('#eventModal').modal('show');
                        }else{
                            alert('error fetching event detail');
                        }
                    },'json');

                console.log(event);
            }
            // put your options and callbacks here
        })

    });
</script>
@stop