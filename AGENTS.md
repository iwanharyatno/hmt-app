# AGENTS.md

## Stack & Commands

- **Stack:** Laravel 12 / PHP ^8.2 / MySQL 8.3 / Vite + Tailwind 4 + Alpine.js + SweetAlert2/Trix. Excel via `maatwebsite/excel`.
- **Dev (concurrent serve+queue+pail+vite):** `composer dev` — wraps `npx concurrently ... "php artisan serve" "php artisan queue:listen --tries=1" "php artisan pail --timeout=0" "npm run dev"` (`composer.json:52`). Or run singly: `php artisan serve`, `npm run dev` / `npm run build`.
- **Test:** `composer test` (`php artisan config:clear && php artisan test`) — `phpunit.xml` uses `sqlite :memory:`. Single file: `php artisan test --filter=TestName`. Only `tests/Feature/ExampleTest.php` + `tests/Unit/ExampleTest.php` exist.
- **Lint/format:** `vendor/bin/pint` (Laravel Pint). No JS linter configured.
- **Docker:** `docker compose up -d` (app `hmt_app` + `nginx_hmt:8800` + `mysql_hmt`). DB in compose is `DB_HOST=mysql_hmt`/`DB_DATABASE=laravel`/`laravel:secret`; local `.env` defaults to `127.0.0.1`/`db_hmt`/`root`. `deploy.sh` does `down` → rm `app_code` volume → `build --no-cache` → `up -d` → `composer install --no-dev` → `migrate --force` → `config:cache route:cache view:cache` + `storage:link`.
- **Setup:** `copy .env.example .env && php artisan key:generate && php artisan migrate && php artisan storage:link` (required — HMT images served via `Storage::url()` from `storage/app/public/hmt/{questions,answers}`). `HMT_ADMIN_PASSWORD` in `.env.example:67`.

## Architecture

- **Single app, two quiz domains:** Hagen Matrices Test (HMT, image matrices) + Learning Style Questionnaire / FSLSM (LSQ, text). No packages/monorepo.
- **Entrypoints:** `routes/web.php` — `prefix('user/quiz')` (auth) + `prefix('admin')` (`auth` + `EnsureUserIsAdmin`). Root `/` → `user.dashboard`.
- **Controllers:** `App\Http\Controllers\QuizController` = HMT session lifecycle + LSQ preface; `Admin\LearningStyleController` handles both admin CRUD *and* `POST user/learning-style/submit`; `Admin\HmtController` = question CRUD + histories/exports; `Admin\SettingController` + `AdminController`.
- **Models:** `HmtQuestion` (`question_path`, `answer_paths` JSON, `correct_index`, `is_active`/`is_example`) ↔ `HmtSession` (`user_id`, `attempts`, `started_at`/`finished_at`) → `HmtHistory` (`session_id`, `question_id`, `answer_index` nullable, `answered_at` nullable). `LearningStyleQuestion` (`question`, `answers` JSON `[ {text, point} x2 ]`, `is_active`) → `LearningStyleResult` (`user_id`, `result` string, `details` JSON, `completed_at`); `LearningStyleHistory` table exists but **never written** by current `submit` flow. `Setting` key/value (constants in `app/Models/Setting.php:14`).
- **Views:** `resources/views/user/quiz/hmt.blade.php` + `learning-style.blade.php` are Alpine.js SPAs driving all quiz logic. Admin views under `resources/views/admin/{hmt,learning-style}`.
- **Custom MySQL grammar** `app/Database/Query/Grammars/MySqlGrammar.php:8` overrides `getDateFormat()` to `Y-m-d H:i:s.v` (ms precision).

## Scoring & Result Recording

- **HMT — no `score` column.** Score is derived by comparing `HmtHistory.answer_index` vs `HmtQuestion.correct_index`. Only persisted as rows in `hmt_histories`; aggregate is computed in exports/history view and client-side `totalCorrect` array (`resources/views/user/quiz/hmt.blade.php:234`) which is **never saved server-side**.
  - `QuizController::submitAnswer` (`app/Http/Controllers/QuizController.php:120`) inserts one `HmtHistory` per question and returns `is_correct` — but line 145 has bug: `isset(...) && $validated['answer_index'] && ... ==` treats `0` as falsy, so correct answer at index 0 is reported `is_correct: false`. Same bug in `app/Exports/HmtHistoriesExport.php:56` / `HmtHistorySingleExport.php:51` (`$answerIndex && $answerIndex === $correctIndex`). Fix requires `$answerIndex !== null`.
  - Timeout/no-answer is `answer_index: null` + `answered_at: null` — allowed since `2026_01_13_211555_modify_hmt_histories_record_empty_answers.php` (both nullable). Timer timeout calls `submitAnswer(id, null, true)` with `skipAnswered=true`.
  - Single-question correctness shown only in `admin/hmt/show.blade.php` via `histories.question`.
- **LSQ / FSLSM — `LearningStyleController::submit` (`app/Http/Controllers/Admin/LearningStyleController.php:20`):**
  - Groups answers by `dimension = (i % 4) + 1` and sums `point` per dimension. Mapping: 1 Active–Reflective, 2 Sensing–Intuitive, 3 Visual–Verbal, 4 Sequential–Global. Intensity: `abs >=9 Strong`, `>=5 Moderate`, `>=1 Mild`, else `Balanced`. Direction = positive if sum>0, negative if <0, else `Balanced`.
  - Stores one row in `learning_style_results` with `result` = comma-joined `"Intensity Direction"` and `details` JSON array of 4 `{dimension, score, direction, intensity}`. Frontend shows result inline after submit; admin history remaps to Pemrosesan/Persepsi/Input/Pemahaman.
  - One submission per user enforced (`QuizController::learningStyle:22` redirects if `learningStyleResults` exists). Frontend paginates 11/page (`learning-style.blade.php:197`), blocks `nextPage`/`submit` via `allAnsweredOnPage`. Sends `{ answers: { [globalIndex]: {index, point} }, completed_at: ISO }`.

## Time Sensitivity (HMT — Critical)

- **All timestamps are client-supplied.** JS sends `new Date()` for `started_at` (`quiz.hmt.start`), `answered_at` (`quiz.hmt.answer`), `finished_at` (`quiz.hmt.finish`); server does `Carbon::parse()` with no validation — trust client clock. No server-side duration enforcement or anti-cheat.
- **Frontend timer** `resources/views/user/quiz/hmt.blade.php:228-290`:
  - Duration from `Setting::HMT_DURATION` (seconds, string; empty = 0/NaN). `HMT_SOAL_FIRST` bool: if false, answers hidden until `timeLeft == floor(totalTime/2)` then revealed; if true, shown immediately.
  - `HMT_SHOW_TIME_LEFT` / `HMT_SHOW_QUESTION_PROGRESS` toggle UI only (Blade `@if` in hmt.blade.php:139/163).
  - `setInterval` 1s → decrement `timeLeft`; on `<=0` auto-calls `submitAnswer(..., null, true)` (null answer) then `nextQuestion()` → `finishQuiz()` which POSTs `finished_at` and clears timer. If user answers early, `submitAnswer` fires async but `nextQuestion` is triggered by click — timer and click paths race; timer also calls `submitAnswer` without awaiting before `nextQuestion`.
- **Precision:** `hmt_sessions.started_at/finished_at` are `timestamp(3)` ms (migration `2026_01_13_222814_add_more_precision_to_hmt_sessions.php`), `hmt_histories.answered_at` is `timestamp(6)` nullable (+ `answer_index` nullable). `attempts` auto-increments via `max(attempts)+1` per user (`QuizController.php:100`).
- **Exports:** `HmtHistoriesExport` takes latest session per user (`MAX(attempts)`), `HmtHistorySingleExport` per session. Both format with `Y-m-d H:i:s.v`. Bulk export has typo `Y--d` at `app/Exports/HmtHistoriesExport.php:65` — fix before relying on `Session Finished`.

## Gotchas

- **Storage link required** — missing `php artisan storage:link` breaks all HMT question/answer images (`Storage::url`).
- **Settings are strings** — `Setting::getValue` returns `?string`; booleans are `boolval(...)` in `QuizController::hmt:58-60`. Empty `HMT_DURATION` yields `Number('') == 0` → timer instantly expires.
- **LSQ gate:** HMT requires LSQ completion unless `WEB_ALLOW_LS` setting enabled (`QuizController::hmt:48-51`). Admin toggles via `settings` page.
- **Seeders:** `DatabaseSeeder` + `LSImportSeeder` + `UserSeeder` — re-running may duplicate.
- **Testing gap:** `phpunit.xml` sqlite in-memory works, but no tests cover scoring/timing — verify manually via browser + check `hmt_histories` / `learning_style_results`.
