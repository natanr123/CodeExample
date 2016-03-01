<?php

namespace AppBundle\Service;
use AppBundle\Entity\RestLog;
use Symfony\Component\HttpFoundation\Request;


class Security extends Singleton
{
    public function isAuthenticated (Request $request)
    {
        $session = $request->getSession();
        if(!$session) {
            return false;
        }
        $user_id = $session->get('user_id');
        if(!$user_id) {
            return false;
        }
        return true;
    }

    public function getSessionUserId(Request $request)
    {
        $session = $request->getSession();
        if(!$session) {
            return false;
        }
        $user_id = $session->get('user_id');
        if(!$user_id) {
            throw new \Exception('User is not logged in');
        }
        return $user_id;
    }

    public function clearSessionUserId(Request $request)
    {
        $request->getSession()->clear();
    }

    public function setSessionUserId(Request $request,$session_user_id)
    {
        $session = $request->getSession();
        $session->set('user_id',$session_user_id);
    }

}