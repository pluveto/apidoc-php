<?php

/**
 * @author Pluveto <i@pluvet.com> 2020-2-13
 */

namespace ApiDoc\Language;

use ApiDoc\InterfaceType\ICommentPipe;

class Classic implements ICommentPipe
{
    const regexBlock = "/^\s*\/\*\*?([^!][.\s\t\S\n\r]*?)\*\//m";
    const regexInline = '/\'[^\']*\'|"[^"]*"|((?:\/\/)(.*)$)/m';

    public static function GetBlockDoc(string $filename)
    {
        $str = file_get_contents($filename);
        $ret = [];
        preg_match_all(self::regexBlock, $str, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE , 0);
        foreach ($matches as $match) {
            $baseLineNumber = substr_count(mb_substr($str, 0, $match[1][1]), PHP_EOL) + 1;
            $raw = ltrim($match[1][0]);

            $lines = explode("\n", $raw);
            $newLines = [];
            $offsetLineNumber = 0;
            foreach ($lines as $line) {
                $offsetLineNumber++;
                $line = trim($line);
                $line = ltrim($line, " \t\n\r\0\x0B*");
                if (empty($line)) {
                    continue;
                }
                $newLines[] = [
                    "value" => $line,
                    "file" => $filename,
                    "lineNumber" => $baseLineNumber + $offsetLineNumber,
                ];
            }
            $ret[] = $newLines;
        }
        return $ret;
    }
    public static function GetInlineDoc(string $str)
    {
        $ret = [];
        preg_match_all(self::regexBlock, $str, $matches, PREG_SET_ORDER, 0);
        foreach ($matches as $match) {
            $line = $match[1];
            $line = trim($line);
            $line = ltrim($line, " \t\n\r\0\x0B*");
            if (empty($line)) {
                continue;
            }
            $ret[] = join("\n", $line);
        }
        return $ret;
    }
}
