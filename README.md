# Cattery Backend

REST API for an online **cat (kitten) cattery marketplace**, built with Laravel. It exposes a versioned JSON:API (`/api/v1`) covering authentication, a cat catalogue, a shopping basket, order placements/management, and transactions — with role-based access (admin vs. customer) protected by Sanctum bearer tokens.

## Stack

- PHP 8.5 · Laravel 13 · MySQL (`DB_DATABASE=mira-cattery`) · Laravel Sanctum
- JSON:API resources (`JsonApiResource`) — collections are flat `data: [...]`, single resources are `{ id, type, attributes, relationships, links }`
- Indonesian administrative regions via the `nusantara` package (migration `2026_06_05_000000_create_nusantara_tables.php` enriches `addresses/provinces`)

## Setup

```bash
composer install
cp .env.example .env        # set DB_DATABASE=mira-cattery and DB creds
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The API listens at `http://localhost:8000/api/v1`. `php artisan migrate --seed` populates the admin user, sample breeds/cats, regions, and demo data (see `database/seeders` + `DatabaseSeeder`).

## Authentication

Sanctum Bearer tokens. Register/login to obtain a token, then send it with every request:

```
Authorization: Bearer <token>
```

**Roles** — a user is an admin when `is_admin` is truthy (`User::isAdmin()`; `User::isAdmin()` also used by an `isAdmin()` helper on the model). Non-admin users are customers. Admin-only endpoints reject non-admins with `403`.

| Endpoint | Method | Access | Description |
| --- | --- | --- | --- |
| `/api/v1/auth/register` | POST | public | `username, email, password, phone`. 201 |
| `/api/v1/auth/login` | POST | public | `email, password`. 200, returns `token` |
| `/api/v1/auth/logout` | POST | auth | Revoke current token. 200 |

## Response envelope

| Kind | HTTP | Shape |
| --- | --- | --- |
| Success | 200/201/204 | `{ status: true, message, data }` (envelope from `ApiResponse` trait) |
| Errors | 4xx/5xx | `{ status: false, message, errors }` (JSON:API-style 422 payloads use `errors` key) |

| Status | Meaning |
| --- | --- |
| `200/201/204` | Ok / created / no content |
| `400` | Malformed request / bad input (`BadRequestResponse`) |
| `401` | Missing/invalid bearer token (`UnauthenticatedResponse`) |
| `403` | Customer hitting an admin-only action, or touching another user's resource (`ForbiddenResponse`) |
| `404` | Resource not found (`NotFoundResponse`) |
| `422` | Validation failed (request form-request rules; 422 with `errors`) |
| `500` | Unexpected server error |

## Endpoints

### Auth
- `POST /api/v1/auth/register` — public. Fields: `username`, `email`, `password` (min 8), `phone`. Creates a customer.
- `POST /api/v1/auth/login` — public. `email`, `password`. Returns `{ token, user }`.
- `POST /api/v1/auth/logout` — auth. Invalidates the current token.

### Users (`/api/v1/users`) — admin management
| Method | Path | Access | Description |
| --- | --- | --- | --- |
| GET | `/api/v1/users` | admin | List (+ `search` via `UserController` scope, pagination `page`/`perPage`) |
| POST | `/api/v1/users` | admin | `username, email, password, phone, is_admin` |
| GET | `/api/v1/users/{user}` | admin or owner | |
| PUT/PATCH | `/api/v1/users/{user}` | admin or owner | |
| DELETE | `/api/v1/users/{user}` | admin | 204 |

### Cats `/api/v1/cats`
| Method | Path | Access | Description |
| --- | --- | --- | --- |
| GET | `/api/v1/cats` | public | Catalogue. Filters: `search` (name/breed/color), `sex`, `status` (Available/Reserved/Sold), `minPrice`/`maxPrice`, `sort` (`name|breed|birthdate|price|created_at`), `asc`. Paginated. |
| GET | `/api/v1/cats/{cat}` | public | Single cat |
| POST | `/api/v1/cats` | admin | `name, breed, sex, color, birthdate, price, description, status, father_id, mother_id, images[]`. 201 |
| PUT/PATCH | `/api/v1/cats/{cat}` | admin | Update cat + images |
| DELETE | `/api/v1/cats/{cat}` | admin | 204 |

**Cat business rules** (`scopeSex/scopeStatus/scopePriceBetween/scopeSearch`, and CreateCatRequest `after()`):
- `price` must be ≥ 0.000; `sex` must be `Male|Female`; `status` one of `Available|Reserved|Sold`.
- Creating a cat marked `Sold` directly is not allowed; status comes from the order flow.
- `father_id`/`mother_id` (if set) must exist, be of the **opposite sex** to this cat, and be **at least 180 days older** than its `birthdate`.
- A referenced parent that is not found → 422 `parent` error.

### Baskets `/api/v1/baskets`
| Method | Path | Access | Description |
| --- | --- | --- | --- |
| GET | `/api/v1/baskets` | auth | Customer → own baskets; admin → all (+ `search`, pagination) |
| POST | `/api/v1/baskets` | auth | `cat_id`. 201. Adds a cat to the customer's basket. |
| GET | `/api/v1/baskets/{basket}` | owner or admin | Basket with its cat |
| PUT/PATCH | `/api/v1/baskets/{basket}` | owner or admin | |
| DELETE | `/api/v1/baskets/{basket}` | owner or admin | 204 |

**Basket rules** (`BasketController::store`, `createBasket`):
- A cat may not be added that is `Reserved` or `Sold` → `422 CatNotAvailable`.
- A cat already in the customer's basket → `422 AlreadyInBasket`.
- `BasketDetail` per cat; same cat cannot appear twice per user.

### Orders `/api/v1/orders`
| Method | Path | Access | Description |
| --- | --- | --- | --- |
| GET | `/api/v1/orders` | auth | Customer → own orders; admin → all (+ `status` filter, `search`). Paginated. |
| POST | `/api/v1/orders` | auth | Create order from the customer's basket. `province, regency, district, village, address`. 201 |
| GET | `/api/v1/orders/{order}` | owner or admin | |
| PUT/PATCH | `/api/v1/orders/{order}` | admin | Update status (see rules) |
| DELETE | `/api/v1/orders/{order}` | admin | 204 |

**Order status flow** (`pending → sending → complete` / `cancelled`):
- `pending` → `sending`: prepare the order; cats become `Reserved` when the order is created.
- `pending` → `cancelled`: cats returned to `Available`.
- `sending` → `complete`: cats become `Sold`.
- `complete`/`cancelled` are terminal — further status updates → `422 OrderStatus`.
- Cancelling an order that already has a transaction → `422`.

### Transactions `/api/v1/transactions`
| Method | Path | Access | Description |
| --- | --- | --- | --- |
| GET | `/api/v1/transactions` | admin | List (+ `search`, `order_id` filter). Paginated. |
| POST | `/api/v1/transactions` | admin | `order_id, amount, transaction_code?, transaction_time?`. 201 |
| GET | `/api/v1/transactions/{transaction}` | admin | |
| DELETE | `/api/v1/transactions/{transaction}` | admin | 204 |

**Transaction rules** (`transactionStore`/`TransactionStoreRequest`):
- Only `pending` orders can receive a transaction → else `422 OrderNotPending`.
- Only one transaction per order (unique `order_id`) → `422 OrderAlreadyHasTransaction`.
- Recording a transaction for a `complete` (already shipped/sold) order → `422`.
- `transaction_code` is unique when provided.

## Exceptions / error handling

Centralised, consistent JSON for: validation (`422` + `errors`), authorization (`401` unauthenticated / `403` forbidden), not found (`404`), and bad requests (`400`). Rules above that fail at runtime return `422` with a specific `errors` key (`cat`, `order`, `transaction`, `basket`, `parent`).

## Testing

The repository intentionally ships **no tests** (probes were used during development and removed). Quality gates that must stay green:

```bash
vendor/bin/pint --dirty          # code style
vendor/bin/phpstan analyse       # static analysis (level 7)
php artisan test                 # no tests → exits clean
```

## Structure

```
app/
  Http/
    ApiResponses/     # generalized response helpers
    Controllers/      # Auth, User, Cat, Basket, Order, Transaction controllers
    Requests/         # Form Requests (validation + query params)
    Resources/        # JSON:API resources (Cat, User, Basket, Order, Transaction, ...)
    ApiTraits/        # ApiResponse, API response traits per controller
  Models/             # User, Cat, CatImage, Basket, Order, OrderDetail, Transaction
bootstrap/app.php     # route/exception wiring
config/               # sanctum, nusantara, api config
database/
  factories/          # model factories
  migrations/         # incl. nusantara region tables
  seeders/            # DatabaseSeeder, AdminSeeder, CatSeeder, ... + DevSeeder
routes/api.php        # /api/v1 routes
```
