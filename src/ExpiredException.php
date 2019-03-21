<?php
namespace Cloud\CloudCsrf;
/**
 * Description of ExpiredException
 * 
 * @author Cloud
 */
class ExpiredException extends \Exception
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

