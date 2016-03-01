<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Note;
use AppBundle\Entity\RestLog;
use AppBundle\Service\Singleton;
use Doctrine\DBAL\DriverManager;

class RestLogRepository extends Singleton
{

    private function createDoctrineConnection()
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


    public function add(RestLog $restLog)
    {
        $arr = $restLog->toArray();
        $values = [];
        $params =[];
        $index =0;
        foreach($arr as $key=>$value) {
            $values[$key]='?';
            $params[$index]=$value;
            $index++;
        }
        $con = $this->createDoctrineConnection();

        $result = $con->createQueryBuilder()
            ->insert('rest_log')
            ->values($values)
            ->setParameters($params)
            ->execute();

        if(!($result)) {
            throw new \Exception('Error adding rest_log');
        }
    }

    public function listAll()
    {
        // http://stackoverflow.com/questions/9260031/doctrine-querybuilder-issues-in-symfony2-usage-questions
        $con = $this->createDoctrineConnection();
        $queryBuilder  = $con->createQueryBuilder('note');
        $stmt = $queryBuilder->select('id','description')->from('note')->execute();
        $results = $stmt->fetchAll();
        return $results;
    }

}