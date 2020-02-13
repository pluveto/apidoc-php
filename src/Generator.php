<?php
/**
 * API 文档生成器 (开发中)
 * @author Pluveto <i@pluvet.com> 2020-2-13
 */

namespace ApiDoc;

define('DEBUG', true);

function rglob($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir . '/' . basename($pattern), $flags));
    }
    return $files;
}

class Generator{
    public static function run($event){
        $argv = $event->getArguments();
        if(DEBUG){
            var_dump($argv);
        }
        $arglist = [];
        foreach($argv as $arg){
            $exp = explode("=", $arg, 2);
            $arglist[trim($exp[0])] = $exp[1];
        }
        foreach (rglob($arglist['in'].'/*.php') as $file) {
            require_once $file;
            // get the file name of the current file without the extension
            // which is essentially the class name
            $classBase = "\\App" . substr(str_replace("/", "\\", dirname($file)), 3);
        
            $className = basename($file, '.php');
            $fullClassName = $classBase . "\\" . $className;
            echo "found file: $file\n";
            if (!class_exists($fullClassName)) {
                continue;
            }
            echo "----->found class: \e[1;36m$fullClassName\e[0m\n";
            fwrite($fRoute, "\$api$className = new $fullClassName();\n");
            $reflector = new \ReflectionClass($fullClassName);
            $functions = $reflector->getMethods(\ReflectionMethod::IS_PUBLIC);
            for ($i = 0; $i < count($functions); $i++) {
                $function = $functions[$i];
                $functionName = $function->getName();
                echo "------->found api: \e[1;35m$functionName\e[0m ";
                $comment = $function->getDocComment();
            }
        }
    }
}