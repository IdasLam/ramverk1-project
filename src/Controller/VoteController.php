<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class VoteController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionPost()
    {
        $vote = new \Ida\Database\Func\Vote();
        $input =  json_decode($this->di->request->getBody());

        $id = $input->id;
        $type = $input->votetype;

        return $vote->votePost($id, $type);
    }
}