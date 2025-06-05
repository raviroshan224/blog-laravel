# Blog Management System API

It includes user authentication, role-based permissions, post and category management, and Excel import/export features.

---

## Features

- **User Authentication** using Laravel Sanctum (token-based).
- **Role and Permission Management** with Spatie Laravel-Permission package.
- Roles: `admin`, `editor`
- Permissions examples: `post-create`, `post-edit`, `post-delete`, `category-manage`, `user-manage`.
- **Post Management**
  - CRUD operations for posts.
  - Posts contain: title, body, category_id, author_id.
  - Only authors or admins can edit/delete posts.
- **Category Management**
  - Admins can create, list, delete categories.
  - Prevent deletion if category has posts.
  - Excel Import/Export for categories using Maatwebsite Excel package.
- **Public Post Listing**
  - Public endpoint to list all posts without authentication.

---

## Tech Stack

- Laravel 10/11  
- Sanctum (API token authentication)  
- Spatie Laravel-Permission (Role & Permission management)  
- Maatwebsite Laravel Excel (Excel import/export)  

---

## Setup Instructions

### Prerequisites

- PHP >= 8.2.12  
- Composer  
- MySQL or any supported database  
- Laravel CLI (optional)  

### Installation Steps

1. Clone the repository:

    ```bash
    git clone https://github.com/raviroshan224/blog-laravel.git
    cd blog-laravel
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Setup `.env` file:

    ```bash
    cp .env.example .env
    ```

    Configure your database and other environment variables in `.env`.

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Run database migrations:

    ```bash
    php artisan migrate
    ```

6. (Optional) Seed roles and permissions:

    ```bash
    php artisan db:seed --class=RolePermissionSeeder
    ```

7. Serve the application:

    ```bash
    php artisan serve
    ```

---

