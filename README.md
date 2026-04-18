# Shadow Mode

Shadow Mode is a real-time coaching platform for interview practice, sales simulations, negotiation drills, and communication training.

Built with:
- Laravel 12 + PHP 8.3+
- Laravel Reverb + Laravel Echo
- Inertia.js + Vue 3 + Tailwind CSS
- MySQL

---

## End-User Guide (Non-Technical)

This section explains how to use the system as a normal user.

### 1) Create Account / Login
- Open the app URL.
- Register a new account or login with existing credentials.
- After login, go to **Dashboard**.

### 2) Create a New Session
- Go to **Create Session** from sidebar.
- Fill session title and scenario type.
- Submit.
- A room is created with a unique **Room Code** (8 characters).

### 3) Join a Session (Room Code Only)
- Go to **Join Session**.
- Enter the room code (example: `9PIXWIDX`).
- Click **Enter Live Room**.

### 4) Work Inside Live Room
- View session state: `waiting`, `live`, `paused`, `ended`.
- Use state buttons to move between session states.
- Use **Coaching Panel** to send hints.
- Use **Push Sample Metric** to record live metric updates.
- See participants and live activity stream.

### 5) Generate and View Report
- When session ends, a summary/report can be generated.
- Open **Session Summary** from room when available.
- If report is not ready yet, system returns you to room with a warning.

### 6) User Roles (Simple)
- `user`: can create, join, and use sessions.
- `coach`: same plus coaching-oriented actions.
- `admin`: full access including admin area.

---

## Technical Guide (Developers)

### 1) Architecture Overview

#### Why Inertia + Vue
Laravel is the backend authority (auth, policies, validation, data), while Vue + Inertia provides SPA-like UX for room interactions.

#### High-Level Layers
- `HTTP Layer`: controllers, form requests, policies
- `Domain Layer`: `app/Domain/ShadowMode/Services/*`
- `Realtime Layer`: broadcast events + Reverb channels
- `Data Layer`: Eloquent models + migrations + seeders
- `UI Layer`: Vue pages, layouts, composables

#### Core Folder Structure
- `app/Domain/ShadowMode/Services`
- `app/Events`
- `app/Listeners`
- `app/Jobs`
- `app/Policies`
- `app/Http/Controllers`
- `app/Http/Requests`
- `app/Models`
- `database/migrations`
- `database/seeders`
- `resources/js/Layouts`
- `resources/js/Components/Shadow`
- `resources/js/Pages/{Landing,Dashboard,Sessions,Rooms,Reports,Admin}`
- `resources/js/Composables/useRoomRealtime.js`

### 2) Database Design

#### Main Tables
- `users`: profile + preferences + activity timestamp
- `training_sessions`: session metadata, scenario, state, host/coach, scheduling, aggregate score
- `session_participants`: participants + presence
- `session_events`: live activity stream
- `coaching_hints`: hints sent during session
- `metric_snapshots`: confidence/clarity/pace/filler/missed question snapshots
- `session_reports`: generated reports
- `report_timeline_moments`: report highlights/moments
- `session_invites`: invite tokens metadata (legacy/optional)
- `roles`, `permissions`, and pivot tables (Spatie)

#### Session State Flow
`waiting` -> `live` -> `paused` -> `ended`

### 3) Backend Implementation

#### Roles
- `user`
- `coach`
- `admin`

#### Policies
- `TrainingSessionPolicy`
- `SessionReportPolicy`

#### Services
- `SessionLifecycleService`
- `CoachingStreamService`
- `SessionReportService`
- `DashboardService`
- `AdminMetricsService`

#### Async Report Pipeline
- `SessionEnded` event
- `GenerateSessionReport` listener
- `BuildSessionReportJob`

### 4) Realtime (Reverb + Echo)

#### Broadcast Events
- `SessionStateUpdated`
- `SessionActivityLogged`
- `CoachingHintPublished`
- `MetricsUpdated`

#### Channels
- Presence channel: `shadow.room.{sessionUuid}` in `routes/channels.php`

#### Frontend Realtime Wiring
- `resources/js/bootstrap.js` initializes Echo/Reverb.
- `resources/js/Composables/useRoomRealtime.js` listens for presence and events.

### 5) Routes Summary
- Public landing + auth + profile
- Dashboard
- Sessions: list/create/store/join/show/leave/update state
- Room endpoints: hints + metrics
- Reports: generate/show
- Admin: dashboard/users/sessions moderation

### 6) Local Setup

#### Prerequisites
- PHP 8.3+
- Composer 2+
- MySQL 8+
- Node.js 20+

#### Install
```bash
composer install
cp .env.example .env
php artisan key:generate
```

#### Configure `.env` (minimum)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shadow_mode
DB_USERNAME=...
DB_PASSWORD=...
```

#### Reverb (if using realtime server)
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=111111
REVERB_APP_KEY=shadow-mode-key
REVERB_APP_SECRET=shadow-mode-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

#### Database
```bash
php artisan migrate --seed
```

#### Frontend
```bash
npm install
npm run build
```

#### Run App (multi-process)
```bash
php artisan serve
php artisan queue:work
php artisan reverb:start
npm run dev
```

### 7) Demo Accounts
- Admin: `admin@shadowmode.test`
- Coach: `coach@shadowmode.test`
- Password (default): `password`

### 8) Testing
```bash
php artisan test
```

### 9) Notes
- Current join flow is **room code only**.
- Social login scaffolding exists but is optional via config flag.
