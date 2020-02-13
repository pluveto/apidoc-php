<?php

namespace ApiDoc\Exception;

use Exception;

class SyntaxError extends Exception
{

    public function __construct($message, $file = "null",  $line = -1)
    {
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
        parent::__construct($this->__toString());
    }

    public function __toString()
    {
        return "Failed to parse: \n    {$this->message}\n        at {$this->file}:{$this->line}\n";
    }
}
