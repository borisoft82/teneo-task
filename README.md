# Teneo task
The task was done by separating the backend and frontend for a clearer structure, easier maintenance and application management. In this case, the backend developer does not have to interact with the frontend part and vice versa.

## Backend
- The project is based on some SOLID, DRY and KISS principles to keep the application scalable and stable for upgrades
- Service Classes were used to focus on managing specific business operations
- Constants were used for core definitions and log messages
- Traits were used for reusing group of methods in different classes

### Backend setup

Install: 
XAMPP (https://www.apachefriends.org/), 

Composer (https://getcomposer.org/download/)

Create MySQL database in phpMyAdmin then open your CLI and run these commands one by one:

```bash
  git clone <project>

  cd backend

  composer install

  cp .env.example .env 
  
  (put your db credentials inside .env)

  php artisan key:generate

  php artisan migrate --seed

  php artisan serve


```

## Frontend
- The frontend part is based on Vue.js framework
- Both Composition API (a new way of building components in Vue 3) and Options API (the traditional way of building Vue components) were used inside codebase. Composition API for ../pages/Archive.vue page and Options API for ../pages/Calendar.vue

### Frontend setup

Install: 
Node.js (https://nodejs.org/en) - I used version v20.10.0 

```bash
  cd frontend

  npm i

  npm run dev
```



