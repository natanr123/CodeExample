<?php
namespace AppBundle\ClientHelper\Response;


use AppBundle\Entity\Note;
use AppBundle\Service\Singleton;

class NoteResponse extends Singleton
{
    /**
     * @param Note[] $notesList
     */
    public function getNoteListResponse($notesList)
    {
        $lines = [];
        foreach($notesList as $key=>$note)
        {
            $date = date('Y-m-d H:i:s',$note->created_on). ' UTC';
            $line = ['id'=>$note->id,'description'=>$note->description,'date'=>$date];
            $lines[] = $line;
        }
        return $lines;
    }
}