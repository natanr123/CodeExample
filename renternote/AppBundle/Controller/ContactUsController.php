<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactUs;
use AppBundle\Entity\Note;
use AppBundle\Repository\ContactUsRepository;
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

class ContactUsController extends Controller
{
    /**
     * @Route("/contact-us")
     * @Method({"POST"})
     */
    public function postContactUs(Request $request)
    {

        RestLogService::getInstance()->doRestLog($request);
        $data = $request->getContent();
        $obj = json_decode($data,true);
        $contactUs = new ContactUs();
        $contactUs->loadFromArr($obj);
        ContactUsRepository::getInstance()->add($contactUs);
        return new Response(json_encode(['success'=>true,'contactUs'=>$contactUs]));
    }
}
