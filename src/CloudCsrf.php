<?php
namespace Cloud\CloudCsrf;
/**
 * Description of CloudCsrf
 * 
 * @author Cloud
 */
class CloudCsrf 
{

    const CSRF_TOKEN='csrf_token'; 
    const CSRF_TOKEN_NAME='_token';
    const CSRF_TOKEN_VALUE=self::CSRF_TOKEN_VALUE;
    
    public function __construct()
    {
        //1. 檢查session_status
        if (session_status() != PHP_SESSION_ACTIVE) {
            throw new CloudCsrfException('Session is not active');
        }
    }
    /**
     * 產生token pairs
     * @param type $name_length token_name長度
     * @param type $value_length token_value長度
     * @param type $expire_secs token存活時間
     */
    public function generateToken($name_length = 8, $value_length = 32, $expire_secs = 1800)
    {
        //0. 設定token有效時間
        $this->updateExpireAt($expire_secs);
        //1. 產生token_name
        //1.1 若已存在則不重複建立
        if (!isset($_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_NAME])) {
            //1.2 產生
            $name = bin2hex(openssl_random_pseudo_bytes($name_length));
            //1.3 存放
            $_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_NAME] = $name;
        }

        //2. 產生token_value
        //2.1 若已存在則不重複建立
        if (!isset($_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE])) {
            //2.2 產生
            $value = bin2hex(openssl_random_pseudo_bytes($value_length));
            //2.3 存放
            $_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE] = $value;
        }
    }
    public function getTokenName()
    {
        return $_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_NAME];
    }
    public function getTokenValue()
    {
        return $_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE];
    }
    /**
     * 檢查是否有設定token pairs
     */
    public function isTokenExist()
    {
        if (!isset($_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_NAME])) {
            throw new NotExistException("Token name does not exist");
        }
        if (!isset($_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE])) {
            throw new NotExistException("Token value does not exist");
        }
    }
    /**
     * 檢查是否有token pairs是否正確
     */
    public function isTokenCorrect($token_name, $token_value)
    {
        if ($token_name !== $_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_NAME]) {
            throw new NotCorrectException("Token name does not exist");
        }
        if ($token_value !== $_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE]) {
            throw new NotCorrectException("Token value does not exist");
        }
    }
    /**
     * 檢查token是否已過期
     */
    public function isTokenExpired()
    {
        $curr_time = time();
        if ($curr_time > $_SESSION[self::CSRF_TOKEN]['_token_expire_at']) {
            throw new ExpiredException("Token has exipred");
        }
    }
    /**
     * 更新token過期時間
     */
    public function updateExpireAt($expire_secs)
    {
        $expire_at = time() + $expire_secs;
        $_SESSION[self::CSRF_TOKEN]['_token_expire_at'] = $expire_at;
    }
}
