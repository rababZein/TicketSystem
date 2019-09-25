# Installation Steps

1- clone this repo and install prerequisites
``` 
$ git clone git@github.com:mohamed-zezo/alferp.git
$ composer install  

```

2- copy `.env.example` to `.env` and edit DB settings to the settings you have on your local machine 

3- run this command 
```
$ php artisan key:generate
$ php artisan jwt:secret
$ php artisan migrate:refresh --seed

```
