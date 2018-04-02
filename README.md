# GMAIL-API 
This project is a mailer using the gmail-api that permits to read, response or send an email

## Getting Started

### Prerequisites
    php 7.1
    php_mailparse.dll installed  (https://pecl.php.net/package/mailparse)
    composer installed   

### Instaling

1.- activate the gmail api in google developers console https://console.developers.google.com/apis/library?project=mail-php-197115&hl=es
2.- in the menu go to api and services- and go to credentials
3.- create a credential id client OAuth
4.- select "web" in application type 
5.- in javascript origin "http://localhost"
6.- in URI's redirect "http://localhost/oauth2callback.php"
7.- Download the json and replace the content in client_secrets.json
8.- in the console in the main folder composer install

## Deployment

For run 
```
php -S localhost:8080

```

## Authors 
* **Diego Chilomer**
