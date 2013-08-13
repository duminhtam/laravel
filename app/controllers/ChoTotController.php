<?php
//use ChoTot;

class ChoTotController extends BaseController {



    public function getIndex()
    {
        $chotot = new ChoTot();
        $data['ads']    = $chotot->getAds();
        $data['config'] = $chotot->getConfig();
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
                return View::make("chotot/push",array("ads"=>$newAds));
            return Response::json();
        }

        $data['ads'] = $chotot->getAds(Input::get('from'), Input::get('to'));

        return View::make("chotot/update",$data);
    }

}