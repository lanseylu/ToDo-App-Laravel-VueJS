# To Do App
## Description
This is a simple To Do App that uses API auth and CRUD operations using the following tech stack:
- **Backend**: Laravel, Sanctum (API auth), MySQL (XAMPP)
- **Frontend**: VueJS, Bootstrap, Axios

## Prerequisites
 - XAMPP PHP >= 8.2.12
 - Node >= 22.5
 - npm v10.9.2
 - Composer v2.5.2

## Steps
1. Clone the repo
2. Install backend dependencies
  ```
  cd todo-app-backend
  composer install
  ```
3. Install frontend dependencies
  ```
  cd todo-app-frontend
  npm install
  ```
4. Set up the environment variables in `todo-backend` by copying the example:
  ```
  copy .env.example .env
  php artisan key:generate
  ```
5. Configure `.env` files in both to `todo-backend` and `todo-frontend` to correct database and port connection
6. **Run Migrations**
  ```
  php artisan migrate
  ```
7. **Run Seeders** To populate the db with users
  ```
  php artisan db:seed
  ```
  Now, you can log in with the ff credentials for testing:
   - **Email**: test@example.com
   - **Password**: password
8. Open XAMPP, start **MySQL**
9. Run the Laravel server in `todo-app-backend`:
  ```
  php artisan serve
  ```
10. Run the frontend development in `todo-app-frontend`:
  ```
  npm run dev
  ```
11. Click the frontend server link, and you should be able to use the app.
