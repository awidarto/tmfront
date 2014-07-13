<?php

class EventController extends BaseController {

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
        $events = Events::get()->toArray();
        return View::make('pages.calendar')
            ->with('title','Events')
            ->with('events',$events);
    }

    public function postData()
    {
        $start = Input::get('start');
        $end = Input::get('end');

        //$daystart = new MongoDate(strtotime($start.' 00:00:00'));
        //$dayend = new MongoDate(strtotime($end.' 23:59:59'));
        /*
        $daystart = new DateTime($start);
        $dayend = new DateTime($end);

        print_r($daystart);
        print_r($dayend);

        $events = Events::where('fromDate','<=',$daystart)
                        ->where('toDate','>=',$dayend)
                        ->get()->toArray();
        */
        $daystart = new MongoDate(strtotime($start.' 00:00:00'));
        $dayend = new MongoDate(strtotime($end.' 23:59:59'));

        $qval = array('fromDate'=>array('$gte'=>$daystart),'toDate'=>array('$lte'=>$dayend));

        $events = Events::whereRaw($qval)->get()->toArray();

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

    public function getCat($slug = null)
    {

        if(is_null($slug)){

            $pages = Page::get()->toArray();

        }else{
            $slug = ucfirst($slug);

            $pages = Page::where('category','=',$slug)->get()->toArray();
        }

        return View::make('pages.pagelist')->with('pages',$pages);
    }

    public function getList($section,$category)
    {
        /*
        if(!is_null($section) && !is_null($category) ){
            $pages = Page::where('section','=',$section)->where('category','=',$category)->get()->toArray();
        }elseif(!is_null($section) && is_null($category) ){
            $pages = Page::where('section','=',$section)->get()->toArray();
        }elseif(is_null($section) && !is_null($category) ){
            $pages = Page::where('category','=',$category)->get()->toArray();
        }else{
            $pages = array();
        }
        */

        $pages = Page::where('section','=',$section)->where('category','=',$category)->get()->toArray();

        return View::make('pages.pagelist')->with('pages',$pages);
    }


    public function getView($slug = null){

        if(!is_null($slug)){
            $page = Page::where('slug','=',$slug)
                ->first()->toArray();
        }else{
            $page = null;
        }
        return View::make('pages.pagereader')->with('content',$page);
    }

    public function missingMethod($parameter = array()){

    }

}