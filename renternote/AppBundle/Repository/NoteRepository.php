<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Note;
use AppBundle\Entity\SimpleEntity;
use AppBundle\Service\DbService;
use AppBundle\Service\Singleton;
use Doctrine\DBAL\DriverManager;

class NoteRepository extends Singleton
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

    /**
     * @param Note $note
     * @throws \Exception
     * Assuming insert will work
     */
    public function add(Note $note)
    {

        $paramsValues = $this->getParamsValues($note);

        $con = DbService::getInstance()->createDoctrineConnection();
        $result = $con->createQueryBuilder()->insert('note')
            ->values($paramsValues['values'])
            ->setParameters($paramsValues['params'])
            ->execute();
            //->setValue('description','?')->setParameter(0,$note->description)
            //->setValue('created_by_user_id','?')->setParameter(1,$note->created_by_user_id)
            //->execute();
        if(!($result)) {
            throw new \Exception('Error adding note');
        }
    }


    public function getParamsValues(SimpleEntity $simpleEntity)
    {
        $arr = $simpleEntity->exportToArray();
        $values = [];
        $params =[];
        $index =0;
        foreach($arr as $key=>$value) {
            $values[$key]='?';
            $params[$index]=$value;
            $index++;
        }
        $paramsValues = ['params'=>$params,'values'=>$values];
        return $paramsValues;
    }


    public function update($id,$postedDescription)
    {
        $con = $this->createDoctrineConnection();

        $query = $con->createQueryBuilder()->update('note')
                ->where("id=$id")
                ->set('description','?')
                ->setParameter(0,$postedDescription);

        // @TODO think of a way how to check that action query was successful
        $result = $query->execute();
    }

    public function delete($id)
    {

        $con = $this->createDoctrineConnection();
        $result = $con->createQueryBuilder()->delete('note')
            ->where("id=$id")
            ->execute();
        if(!($result)) {
            throw new \Exception('Error deleting note');
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

    public function find($id)
    {
        $con = $this->createDoctrineConnection();
        $queryBuilder  = $con->createQueryBuilder('note');
        $stmt = $queryBuilder->select('*')->from('note')
            ->where("id=$id")
            ->execute();
        $results = $stmt->fetchAll();
        if(empty($results)) {
            return null;
        }
        $note = new Note();

        $note->loadFromArr($results[0]);
        return $note;
    }

    public function listAllByCreatedByUserId($user_id)
    {
        // http://stackoverflow.com/questions/9260031/doctrine-querybuilder-issues-in-symfony2-usage-questions
        $con = $this->createDoctrineConnection();
        $queryBuilder  = $con->createQueryBuilder('note');
        $stmt = $queryBuilder->select('*')->from('note')->where('created_by_user_id='.$user_id)->execute();
        $results = $stmt->fetchAll();
        $notesList = [];
        foreach($results as $key=>$row) {
            $note = new Note();
            $note->loadFromArr($row);
            $notesList[] = $note;
        }
        return $notesList;
    }

}