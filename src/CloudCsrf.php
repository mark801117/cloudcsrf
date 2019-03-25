<?php
/**
 * Cloud Csrf 
 *
 * @author Cloud (https://github.com/mark801117)
 */
namespace Cloud\CloudCsrf;
/**
 * 
 * @package Cloud\CloudCsrf
 */
class CloudCsrf 
{
    /** 
     * token key of session 
     * 
     * @var string
     */
    const CSRF_TOKEN = 'csrf_token'; 

    /**
     * token value of session
     * 
     * @var string
     */
    const CSRF_TOKEN_VALUE = '_token_value';
    
    /**
     * Create new csrf
     * 
     * @throws CloudCsrfException if session is not active
     */
    public function __construct()
    {
        //0. 初始化
        if (!isset($_SESSION[self::CSRF_TOKEN])) {
            $_SESSION[self::CSRF_TOKEN] = [];
        }
        //1. 檢查session_status
        if (session_status() != PHP_SESSION_ACTIVE) {
            throw new CloudCsrfException('Session is not active');
        }
    }

    /**
     * 產生token 
     * @param int $expire_secs token存活時間, default: 30mins
     * @param int $value_length token_value長度, default: 32
     * 
     * @return void
     */
    public function generateToken($expire_secs = 3600, $value_length = 32)
    {
        //1. 產生token_value
        //1.1 若已存在則不重複建立
        if (!isset($_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE])) {
            $this->generate($value_length);
        } 
        //1.2 若已過期則重新建立
        if ($this->isExpired()) {
            $this->generate($value_length);
        }
        //2. 設定token有效時間
        $this->updateExpireAt($expire_secs);
    }
    
    /**
     * 取得token value
     * 
     * @return string 
     */
    public function get()
    {
        return $_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE];
    }

    /**
     * 清除token value
     * 
     * @return void
     */
    public function clear()
    {
        unset($_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE]);
    }

    /**
     * 是否有設定token value
     * 
     * @return boolean
     */
    public function isExist()
    {
        return isset($_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE]);
    }

    /**
     * token value 是否正確
     * 
     * @param string $token_value
     * @return boolean
     */
    public function isCorrect($token_value)
    {
        $curr_token_value = $this->get();
        
        return $token_value === $curr_token_value;
    }

    /**
     * 檢查token是否已過期
     * 
     * @return boolean
     */
    public function isExpired()
    {
        $curr_time = time();
        return $curr_time > $_SESSION[self::CSRF_TOKEN]['_token_expire_at'];
    }

    /**
     * 檢查token
     *
     * @param string $token_value 欲檢查之token值
     * @throws NotExistException 當token不存在時
     * @throws NotCorrectException 當token值不正確時
     * @throws ExpiredException 當token過期時
     * @return void
     */
    public function check($token_value)
    {
        //1. 檢查是否存在
        if (!$this->isExist()) {
            throw new NotExistException("Token is not exist");
        }
        //2. 檢查是否正確
        if (!$this->isCorrect($token_value)) {
            throw new NotCorrectException("Token is not correct");

        }
        //3. 檢查是否過期
        if ($this->isExpired()) {
            throw new ExpiredException("Token has expired");
        }
    }

    /**
     * 更新token過期時間
     * @param int $expire_secs never been expired if set "null"
     * 
     * @return void
     */
    public function updateExpireAt($expire_secs)
    {
        if ($expire_secs === null) {
            unset($_SESSION[self::CSRF_TOKEN]['_token_expire_at']);
        } else {
            if (is_int($expire_secs)) {
                $expire_at = time() + $expire_secs;
                $_SESSION[self::CSRF_TOKEN]['_token_expire_at'] = $expire_at;
            }
        }
    }

    /**
     * 產生token
     * @param int $value_length
     * 
     * @return void
     */
    private function generate($value_length)
    {
        $value = bin2hex(openssl_random_pseudo_bytes($value_length));
        $_SESSION[self::CSRF_TOKEN][self::CSRF_TOKEN_VALUE] = $value;
    }
}
