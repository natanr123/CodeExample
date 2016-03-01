<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Service\DbService;
use AppBundle\Service\Singleton;


class UserRepository extends Singleton
{


    public function find($id)
    {
        $con = DbService::getInstance()->createDoctrineConnection();
        $stmt = $con->createQueryBuilder()->select('*')
            ->from('user')->where("id='$id'")->execute();
        $results = $stmt->fetchAll();
        if(!($results)) {
            return null;
        } else {
            $firstUserArr = $results[0];
        }

        $user = new User();
        $user->loadFromArr($firstUserArr);
        return $user;
    }

    public function update($id,$fields)
    {

        $con = DbService::getInstance()->createDoctrineConnection();

        $query = $con->createQueryBuilder()->update('user');
        $index = 0;
        $params = [];
        foreach($fields as $key=>$value) {
            $query ->set($key,'?');
            $params[$index]=$value;
            $index++;
        }
        $results = $query->where("id=$id")
            ->setParameters($params)
            ->execute();

        if($results===null) {
            throw new \Exception('Error updating user');
        }
    }

    public function findByUsername($username)
    {
        $con = DbService::getInstance()->createDoctrineConnection();

        $stmt = $con->createQueryBuilder()->select('*')
            ->from('user')->where("username='$username'")->execute();
        $results = $stmt->fetchAll();

        if(empty($results)) {
            return null;
        } else {
            $firstUserArr = $results[0];
        }

        $user = new User();
        $user->loadFromArr($firstUserArr);
        return $user;
    }

    public function findByEmail($email)
    {
        $con = DbService::getInstance()->createDoctrineConnection();
        $stmt = $con->createQueryBuilder()->select('*')
            ->from('user')->where("email='$email'")->execute();
        $rows = $stmt->fetchAll();

        if(!($rows)) {
            return null;
        }
        $userRow = $rows[0];
        $user = new User();

        $user->loadFromArr($userRow);
        return $user;
    }


    public function add(User $user)
    {
        $con = DbService::getInstance()->createDoctrineConnection();
        $result = $con->createQueryBuilder()->insert('user')
            ->setValue('username','?')->setParameter(0,$user->username)
            ->setValue('password','?')->setParameter(1,$user->password)
            ->setValue('email','?')->setParameter(2,$user->email)

            ->execute();
        if(!($result)) {
            throw new \Exception('Error adding user');
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