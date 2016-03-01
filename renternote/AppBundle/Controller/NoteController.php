<?php

namespace AppBundle\Controller;

use AppBundle\ClientHelper\Response\NoteResponse;
use AppBundle\Entity\Note;
use AppBundle\Repository\NoteRepository;
use AppBundle\Service\RestLogService;
use AppBundle\Service\Security;
use Doctrine\DBAL\DriverManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use PDO;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    /**
     * @Route("/note")
     * @Method({"POST"})
     */
    public function postNoteAction(Request $request)
    {
        RestLogService::getInstance()->doRestLog($request);
        $currentUserId = Security::getInstance()->getSessionUserId($request);
        $data = $request->getContent();
        $obj = json_decode($data,true);
        $note = new Note();
        $note->description = $obj['description'];
        $note->created_by_user_id = $currentUserId;
        $note->created_on = time();
        NoteRepository::getInstance()->add($note);
        return new Response(json_encode($note->exportToArray()));
    }



    /**
     * @Route("/note")
     * @Method({"GET"})
     */
    public function getNoteListAction(Request $request)
    {

        RestLogService::getInstance()->doRestLog($request);
        $currentUserId = Security::getInstance()->getSessionUserId($request);
        $notesList = NoteRepository::getInstance()->listAllByCreatedByUserId($currentUserId);
        $lines = NoteResponse::getInstance()->getNoteListResponse($notesList);
        $response = json_encode($lines);

        return new Response($response);
    }

    /**
     * @Route("/note/{id}")
     * @Method({"GET"})
     */
    public function getNoteAction(Request $request,$id)
    {

        RestLogService::getInstance()->doRestLog($request);

        $currentUserId = Security::getInstance()->getSessionUserId($request);
        $note = NoteRepository::getInstance()->find($id);
        if($note === null) throw new \Exception('Could not find note');
        if($note->created_by_user_id!==$currentUserId) {
            throw new \Exception('You are authorized for this note');
        }


        return new Response(json_encode($note->exportArr()));


    }

    /**
     * @Route("/note/{id}")
     * @Method({"PUT"})
     */
    public function putNoteAction(Request $request,$id)
    {

        RestLogService::getInstance()->doRestLog($request);

        $currentUserId = Security::getInstance()->getSessionUserId($request);
        $note = NoteRepository::getInstance()->find($id);
        if($note === null) throw new \Exception('Could not find note');
        if($note->created_by_user_id!==$currentUserId) {
            throw new \Exception('You are authrized for this note');
        }


        $data = $request->getContent();
        $obj = json_decode($data,true);
        $postedDescription = $obj['description'];


        $note->description = $postedDescription;
        // @TODO do this better
        NoteRepository::getInstance()->update($id,$postedDescription);
        return new Response(json_encode($note->exportArr()));
    }

    /**
     * @Route("/note/{id}")
     * @Method({"DELETE"})
     */
    public function deleteNoteAction(Request $request,$id)
    {

        RestLogService::getInstance()->doRestLog($request);

        $currentUserId = Security::getInstance()->getSessionUserId($request);
        $note = NoteRepository::getInstance()->find($id);
        if($note === null) throw new \Exception('Could not find note');
        if($note->created_by_user_id!==$currentUserId) {
            throw new \Exception('You are authorized for this note');
        }


        $data = $request->getContent();


        // @TODO do this better
        NoteRepository::getInstance()->delete($id);
        return new Response(json_encode(['success'=>true]));


        //return new Response(json_encode($note->exportArr()));


    }





    /**
     * @Route("/note/test")
     */
    public function testAction()
    {

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }



}
