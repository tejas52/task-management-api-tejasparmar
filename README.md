
# Task Management API

## Project Overview
A RESTful Task Management API built with Laravel 12. It allows users to register, authenticate using Sanctum, create projects, and manage tasks under those projects with strict ownership-based authorization and soft delete support.

## Tech Stack
- PHP 8.1+
- Laravel 10.x - 12.x
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
git clone https://github.com/tejas52/task-management-api-tejasparmar/tree/master
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

## API Documentation

### Auth

* POST `/api/register`
    Reqtust : 
        http://local.task.com/api/register
        {
            "name": "Tejas Parmar",
            "email": "tejasparmar1994@gmail.com",
            "password": "Test@12",
            "password_confirmation": "Test@12"
        }

    Response:
        {
            "status": true,
            "message": "User registered successfully",
            "data": {
                "user": {
                    "name": "Tejas Parmar",
                    "email": "tejasparmar1995@gmail.com",
                    "updated_at": "2026-01-07T19:33:16.000000Z",
                    "created_at": "2026-01-07T19:33:16.000000Z",
                    "id": 5
                },
                "token": "10|i3xfZvZOy0Ox4uYxXyuei775EyOx2wGHByUkzH0A6806cc41",
                "token_type": "Bearer"
            }
        }

* POST `/api/login`
    Request:
        http://local.task.com/api/login
        {
            "email":"tejasparmar1991@gmail.com",
            "password":"Test@123"
        }
    Response:
        {
            "status": true,
            "message": "Login successful",
            "data": {
                "user": {
                    "id": 1,
                    "name": "Tejas Parmar",
                    "email": "tejasparmar1991@gmail.com",
                    "email_verified_at": null,
                    "created_at": "2026-01-06T20:47:35.000000Z",
                    "updated_at": "2026-01-06T20:47:35.000000Z"
                },
                "token": "11|o0Rka5hEqy2VDqTzFJiYtMzb1OvdBGTspr1Cfrxt7067b316",
                "token_type": "Bearer"
            }
        }

* POST `/api/logout`
    Request:
        http://local.task.com/api/logout
    Response:
        {
            "status": true,
            "message": "Logged out successfully"
        }

* GET  `/api/user`
    Request:
        http://local.task.com/api/user
    Response:
       {
            "status": true,
            "data": {
                "id": 1,
                "name": "Tejas Parmar",
                "email": "tejasparmar1991@gmail.com",
                "email_verified_at": null,
                "created_at": "2026-01-06T20:47:35.000000Z",
                "updated_at": "2026-01-06T20:47:35.000000Z"
            }
        }

### Projects

* GET    `/api/projects?page=1`
    Request:
        http://local.task.com/api/projects?page=1
    Response:
        {
        "status": true,
        "data": [
            {
                "id": 3,
                "name": "Task Manager2",
                "description": "Laravel 12 Sanctum API",
                "owner": 1,
                "status": "pending",
                "created_at": "2026-01-07T15:21:36.000000Z",
                "updated_at": "2026-01-07T15:21:36.000000Z",
                "deleted_at": null
            },
            {
                "id": 1,
                "name": "Task Manager Updated",
                "description": "Updated project description",
                "owner": 1,
                "status": "in_progress",
                "created_at": "2026-01-07T07:34:00.000000Z",
                "updated_at": "2026-01-07T10:55:35.000000Z",
                "deleted_at": null
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 10,
            "total": 2
        }
    }

* POST   `/api/projects`
    Request:
        http://local.task.com/api/projects
        {
            "name": "Task Manager2",
            "description": "Laravel 12 Sanctum API",
            "status": "pending"
        }
    Response:
        {
            "status": true,
            "message": "Project created successfully",
            "data": {
                "name": "Task Manager2",
                "description": "Laravel 12 Sanctum API",
                "status": "pending",
                "owner": 1,
                "updated_at": "2026-01-07T19:49:30.000000Z",
                "created_at": "2026-01-07T19:49:30.000000Z",
                "id": 4
            }
        }

* GET    `/api/projects/{id}`
    Request:
        http://local.task.com/api/projects/1
    Response:
        {
            "status": true,
            "data": {
                "id": 1,
                "name": "Task Manager Updated",
                "description": "Updated project description",
                "owner": 1,
                "status": "in_progress",
                "created_at": "2026-01-07T07:34:00.000000Z",
                "updated_at": "2026-01-07T10:55:35.000000Z",
                "deleted_at": null
            }
        }

* PUT    `/api/projects/{id}`
    Request:
        http://local.task.com/api/projects/1
        {
            "name": "Task Manager Updated",
            "description": "Updated project description",
            "status": "in_progress"
        }
 
    Response:
        {
            "status": true,
            "message": "Project updated successfully",
            "data": {
                "id": 1,
                "name": "Task Manager Updated",
                "description": "Updated project description",
                "owner": 1,
                "status": "in_progress",
                "created_at": "2026-01-07T07:34:00.000000Z",
                "updated_at": "2026-01-07T08:08:56.000000Z"
            }
        }

* DELETE `/api/projects/{id}`
    Request:
        http://local.task.com/api/projects/1
    Response:
        {
            "status": true,
            "message": "Project moved to trash"
        }

### Tasks

* GET    `/api/projects/{projectId}/tasks?page=1`
    Response : 

* POST   `/api/projects/{projectId}/tasks`
    Resquest:
        http://local.task.com/api/projects/3/tasks
        {
            "title": "Design API",
            "description": "Create nested task APIs",
            "status": "todo",
            "priority": "high",
            "due_date": "2026-01-25"
        }
    Response:
        {
            "status": true,
            "message": "Task created successfully",
            "data": {
                "title": "Design API",
                "description": "Create nested task APIs",
                "status": "todo",
                "priority": "high",
                "due_date": "2026-01-25",
                "project_id": 3,
                "updated_at": "2026-01-07T19:59:13.000000Z",
                "created_at": "2026-01-07T19:59:13.000000Z",
                "id": 5
            }
        }

* GET    `/api/tasks/{taskId}`
    Request:
        http://local.task.com/api/task/5
    Response:
        {
            "status": true,
            "data": {
                "id": 5,
                "title": "Design API",
                "description": "Create nested task APIs",
                "project_id": 3,
                "status": "todo",
                "priority": "high",
                "due_date": "2026-01-25",
                "created_at": "2026-01-07T19:59:13.000000Z",
                "updated_at": "2026-01-07T19:59:13.000000Z",
                "deleted_at": null,
                "project": {
                    "id": 3,
                    "name": "Task Manager2",
                    "description": "Laravel 12 Sanctum API",
                    "owner": 1,
                    "status": "pending",
                    "created_at": "2026-01-07T15:21:36.000000Z",
                    "updated_at": "2026-01-07T15:21:36.000000Z",
                    "deleted_at": null
                }
            }
        }
* PUT    `/api/tasks/{taskId}`
    Request:
        http://local.task.com/api/tasks/5
    Response:
        {
        "status": true,
        "message": "Task updated successfully",
        "data": {
            "id": 5,
            "title": "Design API123",
            "description": "Create nested task APIs",
            "project_id": 3,
            "status": "todo",
            "priority": "high",
            "due_date": "2026-01-25",
            "created_at": "2026-01-07T19:59:13.000000Z",
            "updated_at": "2026-01-07T20:09:48.000000Z",
            "deleted_at": null,
            "project": {
                "id": 3,
                "name": "Task Manager2",
                "description": "Laravel 12 Sanctum API",
                "owner": 1,
                "status": "pending",
                "created_at": "2026-01-07T15:21:36.000000Z",
                "updated_at": "2026-01-07T15:21:36.000000Z",
                "deleted_at": null
            }
        }
    }

* DELETE `/api/tasks/{taskId}`
    Request:
        http://local.task.com/api/tasks/5
    Response:
        {
            "status": true,
            "message": "Task moved to trash"
        }

## Architecture Decisions

* MVC architecture using Laravel best practices
* Token-based authentication via Laravel Sanctum
* Authorization enforced using project ownership checks
* Soft deletes for tasks to allow recovery
* Request validation for all APIs
* Pagination for scalable data handling

## Author

Tejas Parmar
