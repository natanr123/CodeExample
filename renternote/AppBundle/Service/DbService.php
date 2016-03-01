<?php

namespace AppBundle\Service;
use Doctrine\DBAL\DriverManager;

class DbService extends Singleton
{
    public function createDoctrineConnection()
    {
        $dbHost = 'PUT_THE_ADDRESS_HERE.rds.amazonaws.com';
        if( isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']==='127.0.0.1')
        {
            $dbHost ='localhost';
        }

        $connectionParams = array(
            'dbname' => 'DB NAME',
            'user' => 'USERNAME',
            'password' => '******',
            'host' => $dbHost,
            'driver' => 'pdo_mysql',
        );
        $conn = DriverManager::getConnection($connectionParams);
        return $conn;
    }
}