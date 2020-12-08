<?php

namespace Anax\Controller;

// use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;
use Anax\DI\DIMagic;

/**
 * Test the SampleController.
 */
class IPControllerTest extends TestCase
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
        $this->controller = new IPController();
        $this->controller->setDI($this->di);
    }
    
    /**
     * Test the route "index".
     */
    public function testIndexActionPost()
    {
        $this->di->request->setGet('ip-input', "2001%3A0db8%3A85a3%3A0000%3A0000%3A8a2e%3A0370%3A7334");
        
        $res = $this->controller->indexActionGet();
        
        $this->assertEquals($res === null, false);
        
        $this->di->request->setGet('ip-json', "2001%3A0db8%3A85a3%3A0000%3A0000%3A8a2e%3A0370%3A7334");
        $res = $this->controller->indexActionGet();

        $this->assertEquals(gettype($res) === "array", true);
    }
}
