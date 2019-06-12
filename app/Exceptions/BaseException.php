<?php

namespace App\Exceptions;

use App\Traits\MsgTrait;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class BaseException extends Exception
{
    use MsgTrait;
    private $key;
    public function __construct($key='',string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->key = $key;
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {

    }

    public function render($request)
    {
        $errors = [];
        if($this->getMessage()){
            $errors['error_msg'] =  $this->getMessage();
        }
        if($this->getCode()){
            $errors['error_code'] =  $this->getCode();
        }
        return $this->err($this->key,null,[],$errors);
//        return $this->getMessage();
    }

}
