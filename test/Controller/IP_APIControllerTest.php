<?php

namespace Anax\Controller;

// use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;
use Anax\DI\DIMagic;

/**
 * Test the SampleController.
 */
class IP_APIControllerTest extends TestCase
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
        $this->controller = new IP_APIController();
        $this->controller->setDI($this->di);
        
    }
    
    /**
     * Test the route "index".
     */
    public function testIndexActionPost()
    {
        $this->di->request->setBody("{\"ip\": \"216.58.207.195\"}");
        
        $res = $this->controller->indexActionPost();
        
        $this->assertIsArray($res);
        
        $APIResult = $res[0];
        $res = $this->controller->indexActionPost();
        
        $this->assertIsArray($res);
        
        $APIResult = $res[0];
        
        $this->assertEquals($APIResult["ip"], "216.58.207.195");
        $this->assertEquals($APIResult["type"], "IPv4");
        $this->assertEquals($APIResult["valid"], true);
        $this->assertEquals($APIResult["domain"], "arn11s04-in-f3.1e100.net");
        
        $this->di->request->setBody("{\"ip\": \"2001:0db8:85a3:0000:0000:8a2e:0370:7334\"}");
        
        $res = $this->controller->indexActionPost();
        
        $this->assertIsArray($res);
        
        $APIResult = $res[0];

        $this->assertEquals($APIResult["type"], "IPv6");
    }
}
