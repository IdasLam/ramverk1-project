<?php

namespace Anax\Controller;

// use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;
use Anax\DI\DIMagic;

/**
 * Test the SampleController.
 */
class PostControllerTest extends TestCase
{

     /**
     * Setup the controller, before each testcase, just like the router
     * would set it up.
     */
    protected function setUp(): void
    {
        // Init service container $di to contain $app as a service

        $this->di = new DIMagic();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");
        // $app = $this->$di;
        // $this->$di->set("app", $app);
    
        // Create and initiate the controller
        $this->controller = new PostController();
        $this->controller->setDI($this->di);
        $this->di->session->set("username", "admin");
        $this->di->session->set("email", "admin@admin.com");
    }

    public function testIndexActionGet()
    {
        $this->di->request->setGet('tags', "admin");
        
        $res = $this->controller->indexActionGet();
        $this->assertEquals(gettype($res), "object");
        
        $this->di->request->setGet('tags', "");
        $this->di->request->setGet('id', "1");
        
        $res = $this->controller->indexActionGet();
        $this->assertEquals(gettype($res), "object");

        // Test sorting
        $this->di->request->setGet('sort-by', "latest");
        
        $res = $this->controller->indexActionGet();
        $this->assertEquals(gettype($res), "object");

        $this->di->request->setGet('sort-by', "oldest");      
        $res = $this->controller->indexActionGet();
        $this->assertEquals(gettype($res), "object");
        
        $this->di->request->setGet('sort-by', "upvotes");
        
        $res = $this->controller->indexActionGet();
        $this->assertEquals(gettype($res), "object");
        
        $this->di->request->setGet('sort-by', "controversial");
        
        $res = $this->controller->indexActionGet();
        $this->assertEquals(gettype($res), "object");
    }

    public function testNewpostActionPost()
    {
        $this->di->request->setPost("tags", "test");
        $this->di->request->setPost("content", "hello");

        $res = $this->controller->newpostActionPost();
        $this->assertEquals(gettype($res), "object");
    }
    
    public function testSearchTagActionGet()
    {
        $this->di->request->setGet("search", "test");

        $res = $this->controller->searchTagActionGet();
        $this->assertEquals(gettype($res), "object");
    }
}
