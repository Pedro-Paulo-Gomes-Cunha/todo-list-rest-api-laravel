# todo-list-rest-api-laravel

# Project: Task Management API (Todo List)

This is a **public** backend API built with Laravel to manage tasks (Todo List). It provides a RESTful Application Programming Interface (API) for CRUD (Create, Read, Update, Delete) operations on tasks, without the need for user authentication.

## Features

* **Public Task Management:**
    * Create new tasks.
    * List all tasks.
    * View details of a specific task.
    * Update an existing task (title, description, status).
    * Delete a task.
* **Task Statuses:** Tasks can have the following statuses: `pending`, `in progress`, `completed`.

## Technologies Used

* **PHP:** Main programming language.
* **Laravel Framework:** PHP framework for API development.
* **MySQL / SQLite:** Relational database for data storage (can be configured in `.env`).
* **Composer:** PHP dependency manager.
* **PHPUnit:** Testing framework to ensure code robustness.

## Installation and Setup

Follow these steps to get the project running locally.

### Prerequisites

Make sure you have the following installed on your system:

* PHP >= 8.2
* Composer
* A database (MySQL, PostgreSQL, SQLite, etc.)

### Installation Steps

1.  **Clone the Repository:**
    ```bash
    git clone [<YOUR_REPOSITORY_URL>](https://github.com/Pedro-Paulo-Gomes-Cunha/todo-list-rest-api-laravel.git)
    cd todo-list-rest-api-laravel
    ```

2.  **Install Composer Dependencies:**
    ```bash
    composer install
    ```

3.  **Configure the Environment File (`.env`):**
    * Copy the `.env.example` file to `.env`:
        ```bash
        cp .env.example .env
        ```
    * Generate an application key:
        ```bash
        php artisan key:generate
        ```
    * Edit the `.env` file and configure your database credentials (e.g., `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). For quick development, you can use SQLite:
        ```env
        DB_CONNECTION=sqlite
        # Remove DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD lines if using SQLite
        ```
        *If using SQLite*, create an empty `database.sqlite` file in the `database/` folder:
        ```bash
        touch database/database.sqlite
        ```

4.  **Run Database Migrations:**
    This will create the `tasks` table (and other default Laravel tables, like `migrations` and `personal_access_tokens` if present, but without the `users` table if you removed it completely) in your database.
    ```bash
    php artisan migrate
    ```

5.  **Start the Development Server (optional, for local API testing):**
    ```bash
    php artisan serve
    ```
    The server will be available at `http://127.0.0.1:8000`.

## API Endpoints

The API follows a RESTful pattern. All endpoints are under the `/api` prefix and **do not require authentication**.

### Tasks (`/api/tasks`)

* **List All Tasks:**
    * `GET /api/tasks`
    * **Response:** `200 OK` and an array of `Task` objects.

* **Create a New Task:**
    * `POST /api/tasks`
    * **Request Body (JSON):**
        ```json
        {
            "title": "Buy Milk",
            "description": "Full-fat milk from the supermarket",
            "status": "pending" // Allowed values: "pending", "in progress", "completed"
        }
        ```
    * **Response:** `201 Created` and the created `Task` object.

* **View Details of a Specific Task:**
    * `GET /api/tasks/{id}`
    * **Response:** `200 OK` and the `Task` object.

* **Update an Existing Task:**
    * `PUT /api/tasks/{id}`
    * **Request Body (JSON) - optional fields, only include those you want to update:**
        ```json
        {
            "title": "Buy Bread (done)",
            "description": "Mixed bread",
            "status": "completed" // Updates the task status
        }
        ```
    * **Response:** `200 OK` and the updated `Task` object.

* **Delete a Task:**
    * `DELETE /api/tasks/{id}`
    * **Response:** `200 OK` and a success message.

## Running Tests

To ensure everything is working as expected, you can run the automated tests:

* **Run all tests (Unit and Feature):**
    ```bash
    php artisan test
    ```
* **Run only Unit tests:**
    ```bash
    php artisan test tests/Unit
    ```
* **Run only Feature tests:**
    ```bash
    php artisan test tests/Feature
    ```


