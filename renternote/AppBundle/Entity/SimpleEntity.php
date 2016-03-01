<?php

namespace AppBundle\Entity;


class SimpleEntity
{
    public function exportToArray()
    {
        return (array) $this;
    }

    public function getFields()
    {
        $arr = $this->exportToArray();
        return array_keys($arr);
    }
}