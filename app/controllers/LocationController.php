<?php

class LocationController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |   Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function getIndex()
    {
        $body = Page::where('tags','like','%contact us%')->orWhere('tags','like','%locations%')->first()->toArray();

        $locations = Outlet::get()->toArray();

        $markers = array();
        foreach($locations as $loc){
            if( isset($loc['latitude']) && $loc['latitude'] != '' && isset($loc['longitude']) && $loc['longitude'] != ''){
                $markers[] = '{latLng:['.$loc['latitude'].', '.$loc['longitude'].'], data:\'<b>'.$loc['name'].'</b><br />'.$loc['address'].'\'}';
            }
        }

        return View::make('pages.location')
            ->with('isloc',1)
            ->with('body', $body)
            ->with('markers', $markers)
            ->with('title','Find Us')
            ->with('locs',$locations);
    }

    public function postData()
    {
        $start = Input::get('start');
        $end = Input::get('end');

        $daystart = new MongoDate(strtotime($start.' 00:00:00'));
        $dayend = new MongoDate(strtotime($end.' 23:59:59'));

        $qval = array('fromDate'=>array('$gte'=>$daystart),'toDate'=>array('$lte'=>$dayend));

        $events = Events::whereRaw($qval)->get()->toArray();

        $colors = Config::get('shop.event_colors');

        $res = array();
        foreach ($events as $ev) {
            $dt = array();
            $dt['id'] = $ev['_id'];
            $dt['title'] = $ev['title'];
            $dt['allDay'] = true;
            $dt['start'] = date( 'Y-m-d H:i:s', $ev['fromDate']->sec);
            $dt['end'] = date( 'Y-m-d H:i:s',$ev['toDate']->sec);
            $dt['description'] = $ev['description'];
            $dt['eventDetail'] = $ev;
            $dt['color'] = $colors[ $ev['category'] ];
            $res[] = $dt;
        }

        return Response::json($res);

    }

    public function getDetail($id)
    {
        $event = Events::find($id);

        $card = View::make('pages.eventcard')->with('ev',$event->toArray())->render();

        return Response::json(array('result'=>'OK', 'html'=>$card ));
    }

    public function missingMethod($parameter = array()){

    }

}