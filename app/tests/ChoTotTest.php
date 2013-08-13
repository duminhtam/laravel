<?php
use Way\Tests\Assert AS Test;

class ChoTotTest extends TestCase {
    private $chototInstance;
    private $crawlHTML;
    public function setUp(){
        $this->chototInstance = new ChoTot;
        $this->crawlHTML = $this->chototInstance->_parseHTML();
    }
    public function testIncludeLibraries()
    {
        Test::isTrue(class_exists('Symfony\Component\DomCrawler\Crawler'));
        Test::isTrue(class_exists('Guzzle\Http\Client'));
    }
    public function testVariables(){
        Test::assertClassHasAttribute('_ads', 'ChoTot');
        Test::assertClassHasAttribute('_statusCode', 'ChoTot');
    }
    public function testCleanText(){
        Test::assertEquals("something",$this->chototInstance->_cleanText("\n \t something  "));
    }
    public function testParseHTML(){
        Test::assertNotNull($this->crawlHTML);
        //test status code respond from Guzzle 200
        Test::assertEquals($this->chototInstance->_statusCode,'200');
    }
    public function testResultArray(){
        Test::assertArrayHasKey('data', $this->chototInstance->_ads);
        Test::assertArrayHasKey('result', $this->chototInstance->_ads);
    }
    /**
     * Test the requirement data
     * */
    public function testResultDataArray(){
        $this->chototInstance->_domManipulate();
        $data = $this->chototInstance->_ads->data[0];

        Test::assertNotNull($data->id);
        Test::assertNotNull($data->title);
        Test::assertNotNull($data->price);
        Test::assertNotNull($data->url);
        Test::assertNotNull($data->date);
        Test::assertNotNull($data->img);
        Test::assertNotNull($data->category);
    }
    public function testCheckAds(){
//        var_dump($this->chototInstance->checkAds('a'));
    }
    public function testcleanIMG(){

    }
}