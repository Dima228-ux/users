## Backend
1.  Переходим в директорию

        cd source-code/backend

2.  Копируем .env.example в .env
3.  Скачиваем зависимости

        composer install
4.  Создаем базу данных

        CREATE USER "users-laravel" WITH PASSWORD '12345';
        CREATE DATABASE "users-laravel" WITH OWNER 'users-laravel';
5.  Запускаем миграции базы данных:

        php artisan migrate
6.  Запускаем сидеры:

        php artisan db:seed

7.  Генерируем ключ приложения:

        php artisan key:generate

8.  Запускаем сервер

        php artisan serve
9. Админка находится по http://127.0.0.1:8000/admin
10. Запрос на регистрацию юзера
METHOD: POST; URL:http://127.0.0.1:8000/api/sign-up
   
    Request:
    ```json
    {
    "name": "Admin admin",
    "email":"admin2@mail.ru",
    "password":"Cott2289!",
    "password_confirmation":"Cott2289!",
    "phone":"+79610012169",
    "telegram": "@lenin071919"
    }
    ```
    Response:
    ```json
    {
    "user": {
        "id": 5,
        "email": "admin22@mail.ru",
        "name": "Admin admin",
        "phone": "+79610012169",
        "telegram": "@lenin071919",
        "settings": null
    }
    }
    ```
11. Запрос на авторизацию юзера
    METHOD: POST; URL:http://127.0.0.1:8000/api/sign-in
   
    Request:
    
```json
   {
  "email":"admin5@mail.ru",
  "password":"Cott2289!"
   }
   ```
Response:
```json
{
    "token": "3|HsdaIVW28tQG5QynIk65sqPPMaWPde562MIpxd9sfbce576b"
}
```
12. Для запроса 13-17  нужно ввести BEAR token полученный при авторизации
13. Получить информацию о пользовтеле
METHOD: GET; URL: http://127.0.0.1:8000/api/user/
    
    Response:
```json
{
    "user": {
        "id": 5,
        "email": "admin5@mail.ru",
        "name": "Admin Adminov",
        "phone": "+79610012166",
        "telegram": "@lenin31",
        "settings": {
            "test": true,
            "set": false,
            "black": true
        }
    }
}
```
14. Обновить данные о пользователе
METHOD: PUT; URL:http://127.0.0.1:8000/api/user/
    
    Request:
```json
 {
  "email":"admin5@mail.ru",
  "old_password":"Cott2290!",
  "password":"Cott2291!",
  "password_confirmation":"Cott2291!",
  "name":"Admin Adminov",
  "phone":"+79610012166",
  "telegram": "@lenin31"
}
```
Response:
```json
{
    "user": {
        "id": 5,
        "email": "admin5@mail.ru",
        "name": "Admin Adminov",
        "phone": "+79610012166",
        "telegram": "@lenin31",
        "settings": {
            "test": true,
            "set": false,
            "black": true
        }
    }
}
```
15. Обновить настройки пользователя
METHOD: POST; URL: http://127.0.0.1:8000/api/user/
    
    Request
    
```json
{
    "type":3,
     "settings":{
        "test": true,
        "set":false,
        "black":true
     }
}
```
 Response:
 ```json
{
    "code": 265820
}
```

16. Потверждение кода
METHOD: POST; URL: http://127.0.0.1:8000/api/user/verification-settings
    
    Request:
```json
{
    "code": 265820
}
```
Response:
```json
{
    "status": "success"
}
```
17. Выход из профиля
METHOD: DELETE; URL: http://127.0.0.1:8000/api/sign-out/

Response:
```json
[]
```
