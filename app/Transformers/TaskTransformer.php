<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 11/01/16
 * Time: 18:25
 */

namespace App\Transformers;


class TaskTransformer extends Transformer
{


    public function transform($task)
    {


        return [
            'name' => $task['name'],
            'priority' => (integer)$task['priority'],
            'done' =>(boolean) $task['done']
        ];



    }

}