<?php

/**
 * @author Pluveto <i@pluvet.com>
 * Match @ api xxx
 */


namespace ApiDoc\Parser;

use ApiDoc\Exception\SyntaxError;
use ApiDoc\Util\ArrayHelper;

class ApiName
{
    public static function parse($line): array
    {
        $ret = [];
        $re = '/(\s+)/';
        $line["value"] = preg_replace($re,"_", $line["value"]);
        if(empty($line["value"])){
            return null;
        }
        $ret["name"] = $line["value"];


        return $ret;
    }
}
