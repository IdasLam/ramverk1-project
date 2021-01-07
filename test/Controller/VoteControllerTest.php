<?php

namespace Anax\Controller;

// use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;
use Anax\DI\DIMagic;

/**
 * Test the SampleController.
 */
class VoteControllerTest extends TestCase
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
        $this->controller = new VoteController();
        $this->controller->setDI($this->di);
        $this->di->session->set("username", "admin");
    }

    public function testIndexActionGet()
    {
        // Test post
        $this->di->request->setBody("{\"id\": \"1\", \"vote\": \"1\", \"vote\": \"1\"}");
        $res = $this->controller->indexActionPost();
        var_dump(gettype($res));
    }
}
