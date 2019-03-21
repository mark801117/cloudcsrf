<?php
namespace Cloud\CloudCsrf;
/**
 * Description of NotCorrectException
 * 
 * @author Cloud
 */
class NotCorrectException extends \Exception
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

