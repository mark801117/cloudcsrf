<?php
namespace Cloud\CloudCsrf;
/**
 * Description of NotExistException
 * 
 * @author Cloud
 */
class NotExistException extends \Exception
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

