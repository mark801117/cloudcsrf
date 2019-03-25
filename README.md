# cloudcsrf
csrf token generator

## Introduce

配合MVC架構的網站在使用到Single Page Application的Csrf Token驗證

## Example

步驟1: 
於渲染頁面的Controller中產生token

```php

$csrf = new CloudCsrf();
$csrf->generateToken();

```

步驟2: ajax呼叫的middleware中寫入驗證

```php

// ...

try {
    $token_value = "test123"; //request 帶入的token_value
    //1. 驗證token
    $csrf->check();
    
    // ...
    
} catch (NotExistException $ex) {
  
  // ...
  
} catch (NotCorrectException $ex) {
    
    // ...
    
} catch (ExpiredException $ex) {

    // ...
    
}

// ...

```
