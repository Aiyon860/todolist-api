
# Todo List API

A Laravel 13-based RESTful API for managing todo items. This project is part of a **home test** for an Backend Developer internship position at PT Dynamic Talenta Navigator.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Setup & Installation](#setup--installation)
- [API Usage](#api-usage)
- [Notes](#notes)
- [Credentials](#credentials)

---

## Features

- **Todo CRUD** (`index` & `store` implemented)
- **Filtering & Search**: filter by title, assignee, status, priority, due date, and time tracked
- **Pagination**: returns 10 items per page
- **Excel Export**: download todos as `.xlsx` with summary (total items + total time)
- **Consistent JSON Response**: format: `{ success, message, data }`

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13.8+ |
| PHP | 8.3+ |
| Database | SQLite |
| Excel Export | maatwebsite/laravel-excel |

---

## Setup & Installation

### Prerequisites

- PHP 8.3+
- Composer

### Quick Start

```bash
git clone <repo-url> todo-list
cd todo-list

# Install dependencies, setup .env, generate key, and migrate
composer setup
```

Or manually:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Run Server

```bash
composer dev
```

Or:

```bash
php artisan serve
```

Server will run at `http://localhost:8000`.

---

## API Usage

### Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/todos` | List todos (with filters & pagination) |
| `POST` | `/api/todos` | Create a new todo |
| `GET` | `/api/todos?format=excel` | Download todos as Excel file |

### Filters (GET /api/todos)

| Parameter | Example | Description |
|-----------|---------|-------------|
| `title` | `?title=learn` | Search by title (LIKE) |
| `assignee` | `?assignee=john,jane` | Filter by assignee (comma = OR) |
| `status` | `?status=open,in_progress` | Filter by status (comma = OR) |
| `priority` | `?priority=high,medium` | Filter by priority (comma = OR) |
| `start` / `end` | `?start=2025-01-01&end=2025-12-31` | Due date range |
| `min` / `max` | `?min=30&max=120` | Time tracked range (minutes) |

### Example Requests

**Create a new todo:**

```bash
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Learn Laravel",
    "assignee": "John",
    "due_date": "2025-08-01",
    "priority": "high",
    "status": "pending"
  }'
```

**Download Excel:**

```bash
curl -o todos.xlsx "http://localhost:8000/api/todos?format=excel"
```

**List todos (filter by status + priority) with Excel Format:**

```bash
curl "http://localhost:8000/api/todos?format=excel&status=open,in_progress&priority=high"
```

**List todos (filter by status + priority) with JSON format:**

```bash
curl "http://localhost:8000/api/todos?status=open,in_progress&priority=high"
```

### Enum Values

**Status:** `pending`, `open`, `in_progress`, `completed`

**Priority:** `low`, `medium`, `high`

---

## Postman Collection

Check out the [Postman Collection](https://danielaiyons-team.postman.co/workspace/My-Workspace~86126535-67f1-4df8-8ce1-ae85dfe167d6/collection/32072198-5bbb6813-5061-4907-b094-ca461450cfe8?action=share&source=copy-link&creator=32072198) for more info about the API Documentation.

## Notes

This project was completed as part of a home test for an internship. Key evaluation areas:

1. **Laravel understanding**: routing structure, controllers, models, migrations, and request handling
2. **Code quality**: form request validation, API resources, enums, and consistent JSON response format
3. **Problem solving**: flexible filtering implementation
4. **Documentation**: this README itself

---

## Credentials

- **Name:** Daniel Adi Pratama
- **Email:** danieladipratama860@gmail.com
- **Phone:** +6282228678208
- **Campus:** Politeknik Negeri Semarang
- **Study Program:** D4 - Teknologi Rekayasa Komputer
- **Portfolio:** https://danielaiyon.net
