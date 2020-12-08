<?php

namespace Anax\Controller;

// use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;
use Anax\DI\DIMagic;

/**
 * Test the SampleController.
 */
class WeatherControllerTest extends TestCase
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
        $this->controller = new WeatherController();
        $this->controller->setDI($this->di);
    }
    
    /**
     * Test the route "index".
     */
    public function testIndexActionGet()
    {
        $this->di->request->setGet('ip-input', "216.58.211.142");
        
        $res = $this->controller->indexActionGet();
        
        $this->assertEquals($res === null, false);
        
        $this->di->request->setGet('ip-input', "216.58.2");
        
        $res = $this->controller->indexActionGet();
        
        $this->assertEquals($res->loc === null, true);
        
        $this->di->request->setGet('ip-json', "216.58.211.142");
        $res = $this->controller->indexActionGet();
        
        $this->assertEquals(gettype($res) === "array", true);
    }
}
