<?php

/**
 * @author Pluveto <i@pluvet.com>
 * Match @ api xxx
 */


namespace ApiDoc\Parser;

use ApiDoc\Exception\SyntaxError;
use ApiDoc\Util\ArrayHelper;

class Api
{
    public static function parse($line): array
    {
        $ret = [];
        $re = '/^(?:(?:\{(.+?)\})?\s*)?(.+?)(?:\s+(.+?))?$/';
        preg_match($re, $line["value"], $matches);
        if (ArrayHelper::countEmpty($matches) >= 3) {
            throw new SyntaxError(
                "Api syntax error: You should give at least `type` and `url` in @api syntax",
                $line["file"],
                $line["lineNumber"]
            );
        }
        $ret["type"] = $matches[1];
        $ret["url"] = $matches[2];
        $ret["title"] = ArrayHelper::getWhenHasKey($matches, 3, "");


        return $ret;
    }
}
