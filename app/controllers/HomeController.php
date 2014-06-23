<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getIndex()
	{

        $headslider = Homeslider::where('publishing','published')
                                ->where('widgetLocation','Home Top')
                                ->orderBy('sequence','asc')
                                ->get()->toArray();

        $hello = Product::where('tags','like','%hello%')
                            ->orderBy('createdAt','desc')
                            ->get()->toArray();

        $goodbuy = Product::where('tags','like','%good buy%')
                            ->orderBy('createdAt','desc')
                            ->get()->toArray();

        $idea = Posts::where('tags','like','%idea%')
                            ->orderBy('createdAt','desc')
                            ->get()->toArray();

        $welove = Posts::where('tags','like','%we love%')
                            ->orderBy('createdAt','desc')
                            ->get()->toArray();

        $news = Posts::where('section','news')
                            ->orderBy('createdAt','desc')
                            ->get()->toArray();

        $twits = Twitter::getSearch(array('q'=>'toimoi'));

		return View::make('pages.home')
                ->with('hello',$hello)
                ->with('goodbuy',$goodbuy)
                ->with('idea',$idea)
                ->with('welove',$welove)
                ->with('toimoitwit',$twits)
                ->with('news',$news)
                ->with('headslider',$headslider);
	}

}