# Shadow Mode

Shadow Mode is a real-time coaching platform for interview practice, sales simulations, negotiation drills, and communication training.

Built with:
- Laravel 12 + PHP 8.3+
- Laravel Reverb + Laravel Echo
- Inertia.js + Vue 3 + Tailwind CSS
- MySQL (production target)

## 1) Architecture Plan

### Why Inertia + Vue
For maintainability and real-time UX, this app uses Laravel as the backend authority and a Vue SPA-style frontend via Inertia. This keeps routing and auth idiomatic Laravel while enabling fluid room interactions and low-latency UI updates.

### High-Level Layers
- `HTTP Layer`: controllers + form requests + policies
- `Domain Layer`: `app/Domain/ShadowMode/Services/*`
- `Realtime Layer`: broadcast events + Reverb presence/private channels
- `Data Layer`: Eloquent models + migrations + factories + seeders
- `UI Layer`: reusable Vue layout/components + page-level features

### Core Folder Structure
- `app/Domain/ShadowMode/Services`
- `app/Events`
- `app/Listeners`
- `app/Jobs`
- `app/Policies`
- `app/Http/Controllers`
- `app/Http/Requests`
- `app/Models`
- `database/migrations`
- `database/factories`
- `database/seeders`
- `resources/js/Layouts`
- `resources/js/Components/Shadow`
- `resources/js/Pages/{Landing,Dashboard,Sessions,Rooms,Reports,Admin}`
- `resources/js/Composables/useRoomRealtime.js`

## 2) Database Design

## Main Tables
- `users`: profile + preferences + activity timestamp
- `training_sessions`: room metadata, scenario, state, host/coach, scheduling, aggregate score
- `session_participants`: role + presence tracking
- `session_events`: live activity stream
- `coaching_hints`: instant coaching messages
- `metric_snapshots`: confidence/clarity/pace/filler/missed question + overall score samples
- `session_reports`: generated summary and score breakdown
- `report_timeline_moments`: replayable report moments
- `session_invites`: invite tokens, expiry, usage
- `roles/permissions` tables via Spatie package

## States
`waiting` -> `live` -> `paused` -> `ended`

## 3) Backend Implementation

Implemented:
- Role model: `user`, `coach`, `admin`
- Policies:
  - `TrainingSessionPolicy`
  - `SessionReportPolicy`
- Form requests for creation/join/state updates/hints/metrics/admin user updates
- Services:
  - `SessionLifecycleService`
  - `CoachingStreamService`
  - `SessionReportService`
  - `DashboardService`
  - `AdminMetricsService`
- Queue + async report generation:
  - `SessionEnded` -> `GenerateSessionReport` listener -> `BuildSessionReportJob`
- Notifications:
  - `SessionInvitationNotification`
  - `SessionSummaryReadyNotification`

## Routes
- Landing, auth, profile
- Dashboard
- Sessions: index/create/store/join/show/leave/state update
- Live Room page + hint/metric posting endpoints
- Report generate/show
- Admin dashboard/users/sessions moderation

## 4) Frontend Implementation

## Pages Delivered
- Landing page
- Login/Register/Forgot password (Breeze)
- User dashboard
- Session list
- Create session page
- Join session page
- Session detail page
- Live room page (immersive + coaching panel)
- Session report page
- Profile/settings page
- Admin dashboard
- Admin users and sessions moderation pages

## UI/UX Direction
- Premium SaaS-style shell with custom sidebar/topbar
- Light/dark mode toggle
- Reusable visual blocks (`StatsCard`, `TrendChart`, `ActivityList`)
- Atmospheric gradients + subtle grid background + polished card hierarchy
- Responsive layout for desktop/mobile

## 5) Real-Time Wiring (Reverb First)

## Broadcasting
- `SessionStateUpdated`
- `SessionActivityLogged`
- `CoachingHintPublished`
- `MetricsUpdated`

## Channels
- `shadow.room.{sessionUuid}` presence channel in `routes/channels.php`

## Frontend Echo Integration
- `resources/js/bootstrap.js` initializes Echo/Reverb
- `resources/js/Composables/useRoomRealtime.js` subscribes to room and updates:
  - members
  - activity stream
  - hints feed
  - live metrics snapshot
  - state transitions

## 6) Seed Data / Demo Data

`DatabaseSeeder` calls:
- `RoleAndPermissionSeeder`
- `ShadowModeDemoSeeder`

Demo seeder creates:
- admin, coach, and users
- multiple sessions with participants
- activity events
- live metric snapshots
- coaching hints
- invite tokens
- generated reports + timeline moments

## 7) Setup Instructions

## Prerequisites
- PHP 8.3+
- Composer 2+
- MySQL 8+
- Node.js 20+ (for asset build)

## Install
```bash
composer install
cp .env.example .env
php artisan key:generate
```

## Configure `.env`
Use MySQL values:
- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=shadow_mode`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`

Enable Reverb:
- `BROADCAST_CONNECTION=reverb`
- `REVERB_APP_ID=...`
- `REVERB_APP_KEY=...`
- `REVERB_APP_SECRET=...`
- `REVERB_HOST=localhost`
- `REVERB_PORT=8080`
- `REVERB_SCHEME=http`

## Migrate + Seed
```bash
php artisan migrate --seed
```

## Frontend Build
```bash
npm install
npm run build
```

## Run App (multi-process)
Use separate terminals:
```bash
php artisan serve
php artisan queue:listen
php artisan reverb:start
npm run dev
```

## 8) Demo Accounts
After seeding:
- Admin: `admin@shadowmode.test`
- Coach: `coach@shadowmode.test`
- Password: use seeded default (`password`) unless changed in factory

## 9) Test Status
`php artisan test` passes in this repo.

## 10) Notes
- Social login is scaffolded as optional structure (`SocialAuthController`, provider routes, service config placeholders) behind `SOCIAL_LOGIN_ENABLED=false`.
- This implementation is real-time-first: the live room experience is built around Reverb presence + event streams, not a secondary add-on.

