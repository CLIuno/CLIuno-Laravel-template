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
