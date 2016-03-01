<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Note;
use AppBundle\Entity\RestLog;
use AppBundle\Entity\User;
use AppBundle\Repository\NoteRepository;
use AppBundle\Repository\RestLogRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\RestLogService;
use AppBundle\Service\Security;
use Doctrine\DBAL\DriverManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use PDO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    /**
     * @Route("/user/user-registration")
     * @Method({"POST"})
     */
    public function postUserRegistration(Request $request)
    {
        RestLogService::getInstance()->doRestLog($request);
        $data = $request->getContent();
        $obj = json_decode($data,true);
        $existsUserWithUsername = UserRepository::getInstance()->findByUsername($obj['username']);
        if($existsUserWithUsername!==null) {
            return new Response(json_encode(['error'=>'user already exists']));
        }
        if($obj['email']!=='') {
            $existsUserWithEmail = UserRepository::getInstance()->findByEmail($obj['email']);
            if($existsUserWithEmail!==null) {
                return new Response(json_encode(['error'=>'email already exists']));
            }
        }
        $user = new User();
        $user->loadFromArr($obj);
        UserRepository::getInstance()->add($user);
        $registeredUser = UserRepository::getInstance()->findByUsername($user->username);
        Security::getInstance()->setSessionUserId($request,$registeredUser->id);


        $meUser = UserRepository::getInstance()->find($registeredUser->id);
        return new Response(json_encode(['success'=>true,'profile'=>$meUser->getProfile()]));
    }

    // Assumes that you are not logged in
    /**
     * @Route("/user/logging-in")
     * @Method({"POST"})
     */
    public function postLoggingIn(Request $request)
    {
        RestLogService::getInstance()->doRestLog($request);

        if(Security::getInstance()->isAuthenticated($request)) {
            throw new \Exception("You are already logged in");
            exit;
        }


        $data = $request->getContent();
        $obj = json_decode($data,true);


        $loggingInUser = UserRepository::getInstance()->findByUsername($obj['username']);


        if($loggingInUser===null) {
            return new Response(json_encode(['error'=>'username does not exists']));
        }
        if($loggingInUser->password===$obj['password']) {
            $meUser = UserRepository::getInstance()->find($loggingInUser->id);
            Security::getInstance()->setSessionUserId($request,$loggingInUser->id);
            return new Response(json_encode(['success'=>true,'profile'=>$meUser->getProfile()]));
        } else {
            return new Response(json_encode(['error'=>'Wrong password']));
        }
    }

    /**
     * @Route("/user/logging-out")
     * @Method({"POST"})
     */
    public function postLoggingOut(Request $request)
    {

        RestLogService::getInstance()->doRestLog($request);
        Security::getInstance()->clearSessionUserId($request);
        return new Response(json_encode(['success'=>true]));
    }

    /**
     * @Route("/user/me/profile")
     * @Method({"GET"})
     */
    public function getUserMeProfile(Request $request)
    {
        RestLogService::getInstance()->doRestLog($request);
        if(Security::getInstance()->isAuthenticated($request)) {
            $session_user_id = Security::getInstance()->getSessionUserId($request);
            $meUser = UserRepository::getInstance()->find($session_user_id);
            return new Response(json_encode($meUser->getProfile()));
        }
        return new Response(json_encode(null));
    }


    /**
     * @Route("/user/me/profile")
     * @Method({"PUT"})
     */
    public function putUserMeProfile(Request $request)
    {
        RestLogService::getInstance()->doRestLog($request);
        $data = $request->getContent();
        $obj = json_decode($data,true);

        // @TODO make better input validation
        if(count($obj)!==1) {
            return new Response(json_encode(['error'=>'Input error']));
        }
        if(!isset($obj['email'])) {
            return new Response(json_encode(['error'=>'Input error 1']));
        }


        $session_user_id = Security::getInstance()->getSessionUserId($request);
        $existsUserWithEmail = UserRepository::getInstance()->findByEmail($obj['email']);
        if($existsUserWithEmail!==null) {
            $sessionUser = UserRepository::getInstance()->find($session_user_id);
            if($sessionUser->id !== $existsUserWithEmail->id)
            return new Response(json_encode(['error'=>'Email already used by another user']));
        }
        UserRepository::getInstance()->update($session_user_id,$obj);
        return new Response(json_encode(['success'=>true]));

    }



}
