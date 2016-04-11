<?php

/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 11/01/16
 * Time: 18:19
 */

namespace App\Transformers;

abstract class Transformer
{

    public function transformCollection($items){
        return array_map([$this, 'Transform'], $items->toArray());
    }
  /*  public function transform($item)
    {


        return [
            'name' => $item['name'],
            'priority' => $item['priority'],
            'done' => $item['done']
        ];



    }*/

    public abstract function transform($items);
}