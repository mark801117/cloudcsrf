# cloudcsrf
csrf token generator

## 說明Introduce

配合MVC架構的網站在使用到Single Page Application的Csrf Token驗證

## 範例Example

步驟1: 
於渲染頁面的Controller中產生token

```php

$csrf = new CloudCsrf();
$csrf->generateToken();

```

ajax呼叫的middleware中寫入驗證

```php

// ...

try {
    $token_value = "test123"; //request 帶入的token_value
    $token_name = "test321" //request 帶入的token_name
    //1. 驗證token是否存在
    $csrf->isTokenExist();
    //2. 驗證token是否正確
    $csrf->isTokenCorrect($token_name, $token_value);
    //3. 驗證token是否過期
    $csrf->isTokenExpired();
    //4. 每次驗證過舊更新token過期時間
    $csrf->updateExpireAt(1800);
    $result['valid'] = true;
    $result['type'] = 'success';
} catch (NotExistException $ex) {
  
  // ...
  
} catch (NotCorrectException $ex) {
    
    // ...
    
} catch (ExpiredException $ex) {

    // ...
    
}

// ...

```
