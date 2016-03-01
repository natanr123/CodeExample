<?php

namespace AppBundle\Entity;


class RestLog
{
    public $id;
    public $verb;
    public $resource;
    public $post_data;
    public $session_data;
    public $ip2long;

    public function toArray()
    {
        return (array) $this;
    }
}