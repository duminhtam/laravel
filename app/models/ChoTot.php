<?php
use Symfony\Component\DomCrawler\Crawler;
use Guzzle\Http\Client;

class ChoTot extends Eloquent{
    const CHOTOT_URL     = "http://www.chotot.vn/tp_h%E1%BB%93_ch%C3%AD_minh/#";
    const CHOTOT_BASE_URL = "http://www.chotot.vn";
    var $_ads = array("result" => false,"data"=> array());
    var $_statusCode;

    const CONFIG_MAX_COLS = 10; //col
    const CONFIG_RUN_INTERVAL = 1000; //ms
    const CONFIG_IDLE_INTERVAL = 5; //idle second

    /**
     * Get the default configuration base on const
     * */
    public function getConfig(){
        return (object) array("max_cols" => self::CONFIG_MAX_COLS, "runInterval" => self::CONFIG_RUN_INTERVAL, "idleInterval" => self::CONFIG_IDLE_INTERVAL);
    }
    /**
     * Get the current HTML from chotot.vn
     * @return string
     */
    public function _parseHTML(){
        $response = Guzzle\Http\StaticClient::get(self::CHOTOT_URL);
        $this->_statusCode = $response->getStatusCode();
        return $response->getMessage();
    }
    /**
     * Manipulate the HTML from Guzzle
     * @return object
     */
    public function _domManipulate(){
        $crawler = new Crawler($this->_parseHTML());

        $crawler->filter("table.listing_thumbs > tr")->each(function($node, $i) {
            $adsInfo['title'] =  $this->_cleanText($node->filter("td.thumbs_subject > a")->text());
            $adsInfo['price'] =  $this->_getPrice($node->filter("td.thumbs_subject")->html());
            $adsInfo['url'] =  $this->_getPrice($node->filter("td.thumbs_subject > a")->attr('href'));
            $adsInfo['date_posted']  =  $this->_cleanText($node->filter("th.listing_thumbs_date")->text());
            $adsInfo['img']  =  $this->_cleanIMG($node->filter("td.listing_thumbs_image img")->attr('src'));
            $adsInfo['category']  =  $this->_cleanText($node->filter("td.clean_links")->text());

            $adsInfo['created_at'] = date('now');
            //save data
            $this->saveAds($adsInfo);
            //push to data
            $this->_ads['data'][] = $adsInfo;
        });

        if(sizeof($this->_ads) > 0){
            $this->_ads['result'] = true;
        }
        return $this->_ads;
    }
    /**
     * Price parser for td.thumbs_subject
     * @param $string
     * @return string
     * */
    public function _getPrice($string = null){
        $removedA = preg_replace("#<a[^>]*>(.*)</a>#isU","",$string);
        return $this->_cleanText($removedA);

    }

    /**
     * Clean text
     * @param $string
     * @return string
     * */
    public function _cleanText($string = null){
        return trim(strip_tags(utf8_decode($string)));
    }

    /**
     * Get the current homepage Ads in array
     * @return array
     * */
    public function getAds($fromID=null, $toID=null){
        //Run Crawl
        $this->_domManipulate();
        //load Ads from DB
        $db =  DB::table('new_ads')->take(100);
        if($fromID && $toID){
            $db ->whereBetween('id', array($fromID, $toID));
        }
        return $db->get();
    }
    /**
     * Get new ads
     * @return array
     * */
    public function getNewAds($fromID=null){
        if(!$fromID)
            return false;
        $this->_domManipulate();
        $db =  DB::table('new_ads');

        $db->where('id','=',$fromID+1)->take(1);
        return $db->first();
    }
    /**
     * Update Ads index
     *
     * */
    public function _updateIndex(){
        $row = null;
        $col = null;
        $in       = array();
        foreach(Input::get('ads') as $ad){
            $col .= " WHEN {$ad['id']} THEN {$ad['col']} ";
            $row .= " WHEN {$ad['id']} THEN {$ad['row']} ";
            array_push($in, $ad['id']);
        }
        $in = implode(",",$in);

        DB::update("UPDATE ads SET row = CASE id {$row} END, col = CASE id {$col} END WHERE id IN ({$in})");
    }
    /**
     * A quick fix for chotot non-IMG
     * @param string $url
     * @return string $url
     * */
    public function _cleanIMG($url){
        $url = $this->_cleanText($url);
        if(!preg_match("#^http(s?)://#i",$url)){
            $url = self::CHOTOT_BASE_URL . $url;
        }
        return $url;
    }
    /**
     * Save Ads to DB
     * @param array $data
     * */
    public function saveAds($data){
        if(!is_array($data))
            return FALSE;

        if(is_null($this->_checkAds($data['url']))){
            DB::table('ads')->insert(
                $data
            );
        }
    }
    /**
     * Check Ads exists in DB
     * @param string $url
     * @return string $url
     * */
    public function _checkAds($url){
        return DB::table('ads')->where('url', $url)->first();
    }


}