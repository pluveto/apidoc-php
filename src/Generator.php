<?php

/**
 * API 文档生成器 (开发中)
 * @author Pluveto <i@pluvet.com> 2020-2-13
 */

namespace ApiDoc;

define('DEBUG', true);

// 循环遍历文件夹
function rglob($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir . '/' . basename($pattern), $flags));
    }
    return $files;
}

class Generator
{
    public static function run($event)
    {
        $argv = $event->getArguments();
        if (DEBUG) {
            var_dump($argv);
        }
        $arglist = [];
        foreach ($argv as $arg) {
            $exp = explode("=", $arg, 2);
            $arglist[trim($exp[0])] = $exp[1];
        }

        if (!array_key_exists("in", $arglist)) {
            $arglist['in'] = ".";
        }

        foreach (rglob($arglist['in'] . '/*.php') as $file) {
            $blockDocList = \ApiDoc\Language\Classic::GetBlockDoc(realpath($file));
            foreach ($blockDocList as $block) {
                $api = self::processBlock($block);
                var_dump($api);
            }
        }
    }
    public static function processBlock($linesBlock)
    {
        $ret = [];
        foreach ($linesBlock as $line) {
            $re = '/^\@(.*?) /';
            preg_match($re, $line["value"], $matches);
            if (!$matches) {
                continue;
            }

            $annotionType = ucfirst($matches[1]);
            $className = "ApiDoc\\Parser\\$annotionType";
            if (!Class_exists($className)) {
                if (DEBUG) {
                    // eho "Class $className doesn't exist\n";
                }
                continue;
            }

            $line["value"] = ltrim(mb_substr($line["value"], 1 + mb_strlen($matches[1])));
            $parsed = call_user_func("$className::parse", $line);
            $ret = array_merge($ret, $annotionType === "ApiParam" ?
                ["fields" => [$parsed]] : $parsed);
        }
        return $ret;
    }
}
