<?php

namespace Anax\Controller;

// use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;
use Anax\DI\DIMagic;

/**
 * Test the SampleController.
 */
class WeatherAPIControllerTest extends TestCase
{

     /**
     * Setup the controller, before each testcase, just like the router
     * would set it up.
     */
    protected function setUp(): void
    {
        // Init service container $di to contain $app as a service
        global $di;
        global $controller;

        $this->di = new DIMagic();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");
        // $app = $this->$di;
        // $this->$di->set("app", $app);
    
        // Create and initiate the controller
        $this->controller = new WeatherAPIController();
        $this->controller->setDI($this->di);
    }
    
    /**
     * Test the route "index".
     */
    public function testIndexActionPost()
    {
        $this->di->request->setBody("{\"lon\": \"56.1616\", \"lat\": \"15.5866\"}");
        
        $res = $this->controller->indexActionPost();
        
        $this->assertIsArray($res);
        
        $APIResult = $res[0]["result"];
        $APIResult = $res[0]["result"];
        
        $this->assertEquals($APIResult->lat, "15.59");
        $this->assertEquals($APIResult->lon, "56.16");
        
        $this->di->request->setBody("{\"lon\": \"0\", \"lat\": \"0\"}");
        
        $res = $this->controller->indexActionPost();
        $APIResult = $res[0]["result"];

        $this->assertEquals($APIResult, 'Could not get weather report from lontitude and latitude.');
    }
}
