# Splitwise-Like Expense Sharing API

A documentation-aligned Laravel REST API for the intern assignment. It uses **MongoDB only** and implements manual random-token authentication without JWT, Sanctum, Passport, OAuth, or third-party authentication packages.

## Scope
- Registration, login, logout and authenticated profile
- Random `bin2hex(random_bytes(32))` API tokens stored in `tokens`
- Custom bearer-token middleware
- Groups and owner-controlled membership
- Equal, exact and percentage expense splitting
- Group balances and debt simplification
- Settlements with outstanding-debt validation
- Expense pagination, sorting and filtering
- Form Requests, API response wrapper and business exceptions
- MongoDB transaction around expense creation
- Seed data
- Postman collection and API documentation

No bonus features are included.

## Requirements
- PHP 8.2+
- Composer
- MongoDB 6+ (local or hosted)
- PHP MongoDB extension enabled

## Setup
```bash
composer install
copy .env.example .env
php artisan key:generate
```

Set:
```env
DB_CONNECTION=mongodb
MONGODB_URI=mongodb://127.0.0.1:27017
MONGODB_DATABASE=splitwise_like
```

Then:
```bash
php artisan db:seed
php artisan serve
```

Base URL: `http://127.0.0.1:8000/api`

## Authentication
Register or login. The response contains a random token. For protected endpoints use:
```http
Authorization: Bearer YOUR_TOKEN
```
Logout deletes the current token document, so the old token becomes invalid.

## Seed credentials
Every seeded user uses password `password`:
- user1@example.com through user10@example.com

## API Endpoints
### Authentication
- POST `/api/register`
- POST `/api/login`
- POST `/api/logout`
- GET `/api/me`

### Groups
- GET `/api/groups`
- POST `/api/groups`
- GET `/api/groups/{group}`
- PUT `/api/groups/{group}`
- DELETE `/api/groups/{group}`
- GET `/api/groups/{group}/members`
- POST `/api/groups/{group}/members`
- DELETE `/api/groups/{group}/members/{user}`

### Expenses
- GET `/api/groups/{group}/expenses`
- POST `/api/groups/{group}/expenses`
- GET `/api/expenses/{expense}`
- PUT `/api/expenses/{expense}`
- DELETE `/api/expenses/{expense}`

Filters: `payer`, `split_type`, `date_from`, `date_to`, `page`, `per_page`.

### Balances
- GET `/api/groups/{group}/balances`
- GET `/api/groups/{group}/debts`

### Settlements
- GET `/api/groups/{group}/settlements`
- POST `/api/groups/{group}/settlements`

## MongoDB design
Collections: `users`, `tokens`, `groups`, `expenses`, `settlements`.
Participants are embedded in an expense because their share belongs directly to that expense. Users, groups, expenses and settlements reference each other by IDs because those documents are independently queried.

## Important implementation note
MongoDB transactions require a MongoDB deployment that supports transactions (for example a replica set or Atlas). The expense service uses the MongoDB Laravel connection transaction wrapper.
