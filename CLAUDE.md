# CLIuno Laravel template

Laravel 12 REST API serving the CLIuno contract with **Sanctum bearer tokens** under
`/api/v1` (Breeze session auth is kept for web routes — don't remove it): auth
(refresh, reset, email verification, OTP), users, todos, posts+comments, follows, roles.

## Commands

```bash
composer install && php artisan migrate   # setup (sqlite database/database.sqlite)
php artisan serve                         # dev server
php artisan test                          # pest — keep it green
composer lint                             # pint (also the pre-commit hook)
```

## Structure

- `bootstrap/app.php` — `apiPrefix: 'api/v1'` for `routes/api.php`.
- `app/Http/Controllers/Api/AuthController.php` — the token auth surface: login issues an
  `api` token plus a `refresh`-ability token (both Sanctum personal access tokens).
- `app/Services/TotpService.php` — dependency-free RFC 6238 TOTP (sha1/6/30s).
- Resource controllers live in `app/Http/Controllers/`; `admin` middleware gates
  `PATCH/DELETE /users/{id}` and roles.

## Contract rules this codebase follows

- Responses: `{status, message, data}` with the exact keys frontends destructure
  (`data.users/user/todos/todo/posts/post/followers/following/isFollowing`,
  login `data.token` + `data.refreshToken`).
- Requests accept camelCase (`usernameOrEmail`, `refreshToken`, `oldPassword`/`newPassword`,
  `otp`) with snake_case fallbacks.
- One-time tokens live on users (`reset_password_token`, `verify_token`); reset/verify
  look users up **by token**; email verification sets `email_verified_at`.
- The `user` role uses `firstOrCreate` (fresh clone needs no seeding).
- Follows routes live under `/follows/{userId}/...`; users param routes are
  `/users/{id}/posts` and `/users/{id}/roles`.

## Conventions

Pint formatting; conventional commits (commitlint via husky, `pnpm install` once for the
hook tooling); pest tests must stay green.

## AI agents (Claude Code and friends)

- **Check the CLIuno skills first**: `npx cliuno@latest skills status` — if any is
  `missing`/`outdated`, run `npx cliuno@latest skills install` (adds `.claude/skills/cliuno-*`:
  the generator workflow, the scaffold guide, and the shared REST contract).
- **Generate resources, don't hand-write them**:
  `npx cliuno@latest make:resource Product --fields name:string,price:number,inStock:boolean`
  creates `app/Models/Product.php`, `database/migrations/<timestamp>_create_products_table.php`, `app/Http/Controllers/ProductController.php`, `tests/Feature/ProductTest.php` and registers it in `routes/api.php`, `app/Models/User.php`, following this
  template's conventions and the `{status, message, data}` contract. `--dry-run` previews,
  re-running never duplicates registrations, `--force` overwrites.
- Single pieces: `make:model` (→ `php artisan make:model`), `make:controller` (→ `php artisan make:controller`), `make:migration` (→ `php artisan make:migration`), `make:request` (→ `php artisan make:request`), `make:test` (→ `php artisan make:test`). `npx cliuno@latest make` lists them.
- Then edit fields/business logic only, and run the gates above.
