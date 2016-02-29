<?php
/**
 * Created by PhpStorm.
 * User: dispineda
 * Date: 29/02/2016
 * Time: 11:22
 */

namespace App\Transformers;


class TagTransformer extends Transformer
{


    public function transform($tag)
    {


        return [
            'name' => $tag['name'],

            'done' =>(boolean) $tag['done']
        ];



    }

}