<?php

namespace Anax\Controller;

// use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;
use Anax\DI\DIMagic;

/**
 * Test the SampleController.
 */
class LoginControllerTest extends TestCase
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
        $this->controller = new LoginController();
        $this->controller->setDI($this->di);
        $this->di->session->set("username", "admin");
    }

    public function testIndexActionGet()
    {
        $res = $this->controller->indexActionGet();
        $this->assertEquals(gettype($res), "object");
    }
    
    public function testLogoutActionPost()
    {
        $res = $this->controller->logoutActionPost();
        $this->assertEquals(gettype($res), "object");
    }
    
    public function testRegisterActionGet()
    {
        $res = $this->controller->registerActionGet();
        $this->assertEquals(gettype($res), "object");
    }
    
    public function testUserloginActionPost()
    {
        $this->di->request->setPost("username", "admin");
        $this->di->request->setPost("password", "admin");

        $res = $this->controller->userloginActionPost();
        $this->assertEquals(gettype($res), "object");
        
        $this->di->request->setPost("password", "asdasd");
        
        $this->controller->userloginActionPost();
        $error = $this->di->session->get("loginError");
        $this->assertEquals(isset($error), true);
        
        $this->di->request->setPost("username", "adminsad");
        $this->di->request->setPost("password", "admin");

        $this->controller->userloginActionPost();
        $error = $this->di->session->get("loginError");
        $this->assertEquals(isset($error), true);
    }

    public function testSignupActionPost()
    {
        $this->di->request->setPost("username", "admin");
        $this->di->request->setPost("password", "admin2");
        $this->di->request->setPost("email", "admin2@email.com");

        $this->controller->signupActionPost();
        $error = $this->di->session->get("createError");

        $this->assertEquals(isset($error), true);

        $this->di->request->setPost("username", "admin2");
        $this->controller->signupActionPost();
        $username = $this->di->session->get("username");

        $this->assertEquals($username, "admin2");
    }
}
