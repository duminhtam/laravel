<?php
//use ChoTot;

class ChoTotController extends BaseController {


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

        $chotot = new ChoTot();
        $data['ads'] = $chotot->getAds();
        if(!sizeof($data['ads'])){
            Redirect::to('chotot'); //first run redirect
        }
        return View::make("chotot/index",$data);
    }

    /**
     * Update DOM to DB
     * Parse the url chotot/cron to Cron
     * */
    public function getCron(){
        $chotot = new ChoTot();
        $result = $chotot->_domManipulate();


        return Response::json($result);
    }
    /**
     * Position update, filtered by csrf
     * */
    public function postUpdate(){
        $chotot = new ChoTot();
        $chotot->_updateIndex();
    }
    /**
     * Position update, filtered by csrf
     * */
    public function getUpdate(){
        $chotot = new ChoTot();

        if(Input::get('new') == true){
            $result = null;
            $newAds = $chotot->getNewAds(Input::get('from')) ;
            if($newAds)
                $result = "<li data-id='{$newAds->id }' title='{$newAds->title}' class='new'>
           <div class='title'>{$newAds->title}</div>
            <img atl='{$newAds->title}' src='{$newAds->img}'/></li>";
                return Response::make("{$result}");
        }

        $data['ads'] = $chotot->getAds(Input::get('from'), Input::get('to'));

        return View::make("chotot/update",$data);
    }

}