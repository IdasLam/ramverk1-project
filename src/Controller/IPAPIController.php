<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class IPAPIController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionPost()
    {
        $input =  json_decode($this->di->request->getBody());
        $type = "none";
        $valid = false;
        $domain = null;
        $ip = null;


        if (gettype($input) === "object" && $input->ip) {
            $ip = $input->ip;

            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $type = "IPv4";
                $valid = true;
            } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $valid = true;
                $type = "IPv6";
            }
    
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                $domain = gethostbyaddr($ip);
            }
        }

        return [[
            "ip" => $ip,
            "type" => $type,
            "valid" => $valid,
            "domain" => $domain,
        ]];
    }
}
