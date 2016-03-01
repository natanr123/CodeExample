<?php
namespace AppBundle\Service;

use AppBundle\Entity\RestLog;
use AppBundle\Repository\RestLogRepository;
use Symfony\Component\HttpFoundation\Request;

class RestLogService extends Singleton
{
    public function doRestLog(Request $request)
    {
        $restLog = new RestLog();
        $restLog->verb = $request->getMethod();
        $restLog->resource = $request->getRequestUri();
        $restLog->post_data = json_encode($request->getContent());
        $restLog->ip2long  = ip2long($_SERVER['REMOTE_ADDR']);
        $session_data = $request->getSession()->all();

        $restLog->session_data = json_encode($session_data);

        RestLogRepository::getInstance()->add($restLog);
    }
}