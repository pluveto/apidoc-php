<?php

/**
 * @author Pluveto <i@pluvet.com>
 * Match @ api xxx
 */


namespace ApiDoc\Parser;

use ApiDoc\Exception\SyntaxError;
use ApiDoc\Util\ArrayHelper;

class ApiParam
{
    public static function parse($line): array
    {
        $str = $line["value"];
        /**
         * 魔法阵
         */
        $re =
            '/^\s*(?:\(\s*(.+?)\s*\)\s' .
            '*)?\s*(?:\{\s*([a-zA-Z0-9' .
            '()#:\.\/\\\\\[\]_|-]+)\s*' .
            '(?:\{\s*(.+?)\s*\}\s*)?\s' .
            '*(?:=\s*(.+?)(?=\s*\}\s*)' .
            ')?\s*\}\s*)?(\[?\s*([a-zA' .
            '-Z0-9\$\:\.\/\\\\_-]+(?:\[' .
            '[a-zA-Z0-9\.\/\\\\_-]*\])' .
            '?)(?:\s*=\s*(?:"([^"]*)"|' .
            '\'([^\']*)\'|(.*?)(?:\s|' .
            '\]|$)))?\s*\]?\s*)(.*)?$|@/';

        preg_match($re, $str, $matches, PREG_OFFSET_CAPTURE, 0);

        /**
         * 返回的树
         */
        $retBody = [];

        /**
         * --> 参数的名称
         */
        $retBody["field"] = $field =  $matches[6][0];

        /**
         * --> group 字段
         */
        $retBody["group"] = ($group = $matches[1][0]) ? $group : 'Parameter';
        /**
         * --> type 字段
         */
        $type = "string";
        if ($matches[2][0] && (gettype($matches[2][0]) == "string")) {
            if (substr($type, -strlen($type)) === "[]") {
                $type = "array";
            } else {
                $type = $matches[2][0];
            }
        }
        $in  = ["number",  "object"];
        $out = ["integer", "string"];
        $type = str_replace($in, $out, strtolower($type));

        $retBody["type"] = $type;

        /**
         * --> min/max 字段
         */

        $sizeMin = -1;
        $sizeMax = -1;
        $sizeRaw = $matches[3][0];
        if ($sizeRaw && (gettype($sizeRaw) == "string")) {
            [$sizeMin, $sizeMax] = explode($type === "string" ? ".." : "-", $sizeRaw, 2);
        }
        if ($sizeMin != -1)    $retBody["min"] = intval($sizeMin);
        if ($sizeMax != -1)    $retBody["max"] = intval($sizeMax);
        $retBody["size"] = $sizeRaw;

        /**
         * --> options 字段
         */

        $options = [];
        $optionsStr = $matches[4][0];
        if ($optionsStr && (gettype($optionsStr) == "string")) {
            $regExp = "";
            if ($optionsStr[0] === '"')
                $regExp = '/\"[^\"]*[^\"]\"/';
            else if ($optionsStr[0] === '\'')
                $regExp = '/\'[^\']*[^\']\'/';
            else
                $regExp = '/[^,\s]+/';
            preg_match_all($regExp, $optionsStr, $options);
        }
        if (count($options))
        $retBody["allowedValues"] = $retBody["options"] = $options[0];
        
        /**
         * --> required 字段
         */

        if (!($matches[5][0] && $matches[5][0][0] === '[')) {
            $retBody["required"] = true;            
            $retBody["optional"] = false;
        }else{
            $retBody["optional"] = true;
        }
        /**
         * --> default 字段
         */

        $default = null;
        if ($matches[7][0]) $default = $matches[7][0];
        elseif ($matches[8][0]) $default = $matches[8][0];
        elseif ($matches[9][0]) $default = $matches[9][0];

        if ($default) {
            $retBody["defaultValue"] = $default;
        }

        if($description = $matches[10][0]){
            $retBody["description"] = $description;
        }
        /**
         * 完事儿
         */

        return $retBody;
    }
}
