<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ContactUs;
use AppBundle\Entity\Note;
use AppBundle\Service\DbService;
use AppBundle\Service\Singleton;
use Doctrine\DBAL\DriverManager;

class ContactUsRepository extends Singleton
{




    public function add(ContactUs $contactUs)
    {
        $con = DbService::getInstance()->createDoctrineConnection();
        $result = $con->createQueryBuilder()->insert('contact_us')
            ->setValue('email','?')->setParameter(0,$contactUs->email)
            ->setValue('message','?')->setParameter(1,$contactUs->message)
            ->execute();
        if(!($result)) {
            throw new \Exception('Error adding contactUs');
        }
    }





}