# Authentication API using Laravel Sanctum

## Table Of Contents

- [About The Project](#about-the-project)
- [Built With](#built-with)
- [API Documentation](#api-documentation)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)


## About The Project
- A simple RESTful API in Laravel using Sanctum for authentication.
- Allow users to register, log in, fetch their details, and log out.
- CRUD system for managing posts.

## Built With
- **PHP**
- **Laravel**
- **Laravel Sanctum**
- **PHPUnit**
- **MySQL**

## API Documentation
<a href="https://documenter.getpostman.com/view/17672386/2sB2cVgNKz" target="_blank"> API Docs [Postman] </a>

## Getting Started

To get a local copy up and running follow these simple steps.

### Prerequisites

* PHP
* Composer
* Laravel
* MySQL

### Installation
1.Clone the repo
  
  ```sh
      git clone https://github.com/MUSTAFA-Hamzawy/sanctum-posts-app.git
  ```

then, Move to the project directory

2. Make your own copy of the .env file
```sh
    cp .env.example .env
    DB_DATABASE: <DB-name>
    DB_USERNAME= <DB-username>
    DB_PASSWORD: <DB-Password>
```
3. Install dependecies

```sh
    composer install
```
4. Generate a key
```sh
    php artisan key:generate
```
5. Migration & seeders
```sh
    php artisan migrate
    php artisan db:seed
```
6. Run Tests ( Optional )
```sh
    php artisan migrate
    php artisan db:seed
```
7. Start Running
```sh
    php artisan serve
```
