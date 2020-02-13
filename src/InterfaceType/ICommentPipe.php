<?php

namespace ApiDoc\InterfaceType;

interface ICommentPipe{
    public static function GetBlockDoc(string $string);
    public static function GetInlineDoc(string $string);
}

