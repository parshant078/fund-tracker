# Fund Tracker — Chat API

Is doc ka maksad: chat se seedha fund / expense / work add karna, bina browser khole.
Token ek hi hai local + live dono ke liye.

## Auth (2 layer)

**Layer 1 — Server password (sirf LIVE pe, local pe nahi):**
Live URL kholne ke liye HTTP Basic Auth lagta hai.

- User: `parshant129`
- Password: `Parshant@129`
- curl me: `-u parshant129:Parshant@129`

**Layer 2 — API token (local + live dono pe):**
Har `/api/*` request me header bhejo:

```
Authorization: Bearer sWiajnR503nchjVJlf5vthTiJhujRx1zZuVqbqUyMJFqC77XJxcGiolU8C5C
```

## Base URLs

| Kahan | Base URL |
|---|---|
| Local (laptop) | `http://127.0.0.1:8001` |
| Live (server) | `https://idea-creatorz.com/ft-b9ed172a658efe86fccd` |

Live pe har request me Layer 1 + Layer 2 dono lagenge.

## Endpoints (sab JSON)

| Kaam | Method + Path |
|---|---|
| Poora hisaab (totals, balance, category-wise, works, trend) | `GET /api/summary` |
| Fund list | `GET /api/funds` |
| Fund add | `POST /api/funds` |
| Fund dekho / badlo / hatao | `GET /api/funds/{id}` · `PUT /api/funds/{id}` · `DELETE /api/funds/{id}` |
| Expense list / add / dekho / badlo / hatao | `GET /api/expenses` · `POST /api/expenses` · `GET /api/expenses/{id}` · `PUT /api/expenses/{id}` · `DELETE /api/expenses/{id}` |
| Work list / add / dekho / badlo / hatao | `GET /api/works` · `POST /api/works` · `GET /api/works/{id}` · `PUT /api/works/{id}` · `DELETE /api/works/{id}` |
| Work me advance jodo | `POST /api/works/{id}/advance` |
| Category list / add / hatao | `GET /api/categories` · `POST /api/categories` · `DELETE /api/categories/{id}` |
| Invite list / banao / on-off / hatao | `GET /api/invites` · `POST /api/invites` · `POST /api/invites/{id}/toggle` · `DELETE /api/invites/{id}` |

Bina token ke har request `401` degi. Galat data pe `422` + kaunsa field galat hai.

## Fields

**Fund add/badlo:** `donor_name*` (max 100), `amount*` (min 1), `date*` (YYYY-MM-DD), `note` (max 255)
**Expense add/badlo:** `category_id*` (categories list se id), `work_id` (optional), `amount*` (min 1), `date*`, `paid_to` (max 100), `note` (max 255)
**Work add/badlo:** `title*` (max 150), `budget*` (min 0), `advance_paid` (default 0), `status*` (`planned`|`in_progress`|`completed`), `notes`
**Work advance:** `amount*` (min 1)
**Category add:** `name*` (max 60, unique) — jisme expense hai wo category delete nahi hogi
**Invite banao:** `label*` (max 100), `donor_name` (optional)

`*` = zaroori field.

## Chat se direct add karne ke examples

Neeche `TOKEN` aur `BASE` har baar yahi honge:

```bash
TOKEN="sWiajnR503nchjVJlf5vthTiJhujRx1zZuVqbqUyMJFqC77XJxcGiolU8C5C"
LOCAL="http://127.0.0.1:8001"
LIVE="https://idea-creatorz.com/ft-b9ed172a658efe86fccd"
```

**Summary dekho (live):**
```bash
curl -s -u parshant129:Parshant@129 -H "Authorization: Bearer $TOKEN" "$LIVE/api/summary"
```

**Fund add karo (live):**
```bash
curl -s -u parshant129:Parshant@129 -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -X POST "$LIVE/api/funds" \
  -d '{"donor_name":"Yes Bank","amount":50000,"date":"2026-10-19","note":"October fund"}'
```

**Expense add karo (live) — pehle category id lo:**
```bash
curl -s -u parshant129:Parshant@129 -H "Authorization: Bearer $TOKEN" "$LIVE/api/categories"
curl -s -u parshant129:Parshant@129 -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -X POST "$LIVE/api/expenses" \
  -d '{"category_id":1,"work_id":1,"amount":2500,"date":"2026-10-19","paid_to":"Ramesh","note":"Cement"}'
```

**Work add karo + advance do (live):**
```bash
curl -s -u parshant129:Parshant@129 -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -X POST "$LIVE/api/works" \
  -d '{"title":"Boundary Wall","budget":80000,"status":"planned"}'
curl -s -u parshant129:Parshant@129 -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -X POST "$LIVE/api/works/5/advance" -d '{"amount":10000}'
```

Local pe same commands, bas `-u parshant129:Parshant@129` hata do aur `$LOCAL` use karo.

## Token dobara banana ho to

```bash
# naya token banao
NEW=$(php artisan tinker --execute 'echo \Illuminate\Support\Str::random(60)."\n";' | tail -1)
# local DB me lagao
php artisan tinker --execute "App\Models\User::where('email','admin@fund.local')->update(['api_token' => '$NEW']);"
# live DB me lagao (server pe)
ssh -i ~/.ssh/id_idea_creatorz root@168.144.222.247 \
  "cd /var/www/fund-tracker && php artisan tinker --execute \"App\Models\User::where('email','admin@fund.local')->update(['api_token' => '$NEW']);\""
# phir is doc me token wali 3 jagah update karo
```

## Note

- Live DB aur local DB alag files hain — chat se LIVE pe add karoge to live me judenga, LOCAL pe add karoge to local me. Dono sync rakhne ho to bata dena.
- Token kisi se share mat karna — jiske paas token hai wo sab add/delete kar sakta hai.
