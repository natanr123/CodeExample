<?php

namespace AppBundle\Entity;


class ContactUs
{
    public $id;
    public $email;
    public $message;

    public function loadFromArr($arr)
    {
        if(isset($arr['id'])) {
            $this->id = intval($arr['id']);
        }

        $this->email = $arr['email'];
        $this->message = $arr['message'];
    }

    public function exportArr()
    {
        return ['id'=>$this->id,'email'=>$this->email,'message'=>$this->message];
    }

}