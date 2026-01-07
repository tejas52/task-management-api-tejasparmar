
# Task Management API

## Project Overview
A RESTful Task Management API built with Laravel 10. It allows users to register, authenticate using Sanctum, create projects, and manage tasks under those projects with strict ownership-based authorization and soft delete support.

## Tech Stack
- PHP 8.1+
- Laravel 10.x
- MySQL 8.x
- Laravel Sanctum 3.x
- Composer 2.x
- PHPUnit 10.x
- PHPStan 1.x

## Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- Git
- Postman (optional)

## Installation Steps
```bash
git clone https://github.com/your-username/task-management-api.git
cd task-management-api
composer install
cp .env.example .env
php artisan key:generate
````

## Environment Configuration

```env
APP_NAME="Task Management API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

## Database Setup

```bash
php artisan migrate
```

## Running Tests

```bash
php artisan test
```

## Static Analysis

```bash
vendor/bin/phpstan analyse
```

## API Endpoints

### Auth

* POST `/api/register`
* POST `/api/login`
* POST `/api/logout`
* GET  `/api/user`

### Projects

* GET    `/api/projects?page=1`
* POST   `/api/projects`
* GET    `/api/projects/{id}`
* PUT    `/api/projects/{id}`
* DELETE `/api/projects/{id}`

### Tasks

* GET    `/api/projects/{projectId}/tasks?page=1`
* POST   `/api/projects/{projectId}/tasks`
* GET    `/api/tasks/{taskId}`
* PUT    `/api/tasks/{taskId}`
* DELETE `/api/tasks/{taskId}`
* POST   `/api/tasks/{taskId}/restore`
* DELETE `/api/tasks/{taskId}/force`

## Architecture Decisions

* MVC architecture using Laravel best practices
* Token-based authentication via Laravel Sanctum
* Authorization enforced using project ownership checks
* Soft deletes for tasks to allow recovery
* Request validation for all APIs
* Pagination for scalable data handling

## Author

Tejas Parmar

```

---

If you want **even shorter (½ page)** or **company-submission format**, tell me and I’ll compress it further ⚡
```
