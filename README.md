<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Overview 

This project is written with PHP Laravel Framework. The framework is made for writting super API endpoints. It implements passport Authentication
## Installation & Usage
<hr/>

### Downloading the Project


This framework requires PHP 9.2 and mysql database
.  
You can simply clone  `` passport-authentication-api`` like below on your git bash

```bash
git clone https://github.com/Fabulouscode/passport-authentication-api
```
After cloning the project, please run this command on the project directory
```
composer update
```
### Configure Environment
To run the application you must configure the ```.env``` environment file with your database details and mail configurations. Use the following commmand to create .env file. 
```
cp .env.example .env

```
Once you run the above command, your database configuration will be set if you are running your application.

### Please configure your Mail driver in the env to make the application work correctly.
You have to also configure your mail drivers in the .env file, Mailtrap was use for testing

### Clearing Cache and Generating key
Run the following commands on the project directory ```passport-authentication-api```
```
php artisan optimize
php artisan key:generate
php artisan passport:client --personal
```
Run the following command at this stage to run database migrations
```
php artisan migrate
```