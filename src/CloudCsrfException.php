<?php
/**
 *  
 * @author Cloud (https://github.com/mark801117)
 */
namespace Cloud\CloudCsrf;
/**
 * 
 * @package Cloud\CloudCsrf
 */
class CloudCsrfException extends \Exception {
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}