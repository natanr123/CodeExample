<?php
namespace AppBundle\Entity;


class User
{
    public $id;
    public $username;
    public $password;
    public $email;

    private function intKeys()
    {
        return ['id'];
    }

    private function isIntKey($keyName) {
        if($keyName==='id') {return true;}
        return false;
    }

    public function loadFromArr($arr)
    {
        foreach($arr as $key=>$value)
        {
            if(property_exists($this,$key)) {
                if($this->isIntKey($key)) {
                    $this->$key = intval($arr[$key]);
                } else {
                    $this->$key = $arr[$key];
                }

            }
        }
    }

    public function getProfile()
    {
        return ['username'=>$this->username,'email'=>$this->email];
    }
}