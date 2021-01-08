<?php

namespace Anax\Controller;

// use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;
use Anax\DI\DIMagic;

/**
 * Test the SampleController.
 */
class ProfileControllerTest extends TestCase
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
        $this->controller = new ProfileController();
        $this->controller->setDI($this->di);
        $this->di->session->set("username", "admin");
        $this->di->session->set("email", "admin@admin.admin");
    }
    
    public function testIndexActionGet()
    {
        $this->di->request->setGet('user', "admin");
        
        $res = $this->controller->indexActionGet();
        $this->assertEquals(gettype($res), "object");
    }
    
    public function testEditActionGet()
    {
        $res = $this->controller->editActionGet();
        $this->assertEquals(gettype($res), "object");
        
        $this->di->request->setGet('type', "username");
        $this->di->session->set("userEditError", "test error");
        
        $res = $this->controller->editActionGet();
        $this->assertEquals(gettype($res), "object");
    }
    
    public function testEditActionPost()
    {
        // Test change username
        $this->di->request->setPost("edit", "username");
        $this->di->request->setPost("new", "admin");
        
        $res = $this->controller->editActionPost();
        $this->assertEquals(gettype($res), "object");
        
        $this->di->request->setPost("edit", "username");
        $this->di->request->setPost("new", "admim");
        
        $this->controller->editActionPost();
        $username = $this->di->session->get("username");

        $this->assertEquals($username, "admim");

        $this->di->request->setPost("edit", "username");
        $this->di->request->setPost("new", "admin");
        
        $this->controller->editActionPost();

        // Test change email
        $this->di->request->setPost("edit", "email");
        $this->di->request->setPost("new", "admin@admin.com");
        
        $res = $this->controller->editActionPost();
        $this->assertEquals(gettype($res), "object");

        $this->di->request->setPost("new", "test@admin.com");
        
        $this->controller->editActionPost();
        $email = $this->di->session->get("email");
        
        $this->assertEquals($email, "test@admin.com");

        $this->di->request->setPost("edit", "email");
        $this->di->request->setPost("new", "admin@admin.com");
        
        $this->controller->editActionPost();
    }
}
