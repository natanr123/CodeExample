<?php

namespace AppBundle\Entity;


class Note extends SimpleEntity
{
    public $id;
    public $description;
    public $created_by_user_id;
    public $created_on;

    public function loadFromArr($arr)
    {
        $this->id = intval($arr['id']);
        $this->description = $arr['description'];
        $this->created_by_user_id = intval($arr['created_by_user_id']);
        $this->created_on = intval($arr['created_on']);
    }


    public function exportArr()
    {
        return ['id'=>$this->id,'description'=>$this->description,'created_by_user_id'=>$this->created_by_user_id];
    }

}