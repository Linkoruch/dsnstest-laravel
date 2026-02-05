# Database and Permission Fixes

## Problems Fixed

### 1. Missing Columns in test_results Table
**Problem:** The `test_results` table was missing critical columns (`user_id`, `test_id`, `result_file_path`, `completed_at`).

**Solution:** 
- Rolled back the migration: `php artisan migrate:rollback --step=2`
- Re-ran migrations: `php artisan migrate`
- Verified columns were created properly

**Result:** All required columns now exist in the `test_results` table.

### 2. Role Name Mismatch
**Problem:** The navigation was checking for `@role('student')` but the actual role in the database is `user`.

**Solution:**
- Updated `resources/views/livewire/layout/navigation.blade.php` (2 places)
- Changed `@role('student')` to `@role('user')`

**Result:** Users with the `user` role can now see their navigation links.

### 3. JSON Query Issues for SQLite
**Problem:** The query in `AvailableTests.php` was using `whereJsonContains` and `json_extract` which had compatibility issues with SQLite.

**Solution:**
- Updated the query to use `LIKE` patterns for JSON matching
- Now checks for `"all"` string in the JSON array
- Also checks for user IDs in the JSON array

**Result:** Users can now see tests that are:
- Without assigned users (available to all)
- Assigned to "all"
- Assigned to their specific user ID

## Database Structure

### Tables Created:
1. **test_results**
   - id (primary key)
   - user_id (foreign key to users)
   - test_id (foreign key to tests)
   - result_file_path (string)
   - completed_at (timestamp)
   - created_at (timestamp)
   - updated_at (timestamp)

2. **tests**
   - id (primary key)
   - name (string)
   - description (text)
   - questions_file_path (string)
   - assigned_users (json, nullable)
   - created_at (timestamp)
   - updated_at (timestamp)

### Roles:
- **admin** (id=1 or 2) - Administrator role
- **user** (id=3) - Regular user role

## Current Users:
- admin@example.com - admin role
- student@example.com - user role
- linkor2503@gmail.com - user role

## How to Reset Database (if needed):
```bash
# Rollback all migrations
php artisan migrate:rollback --step=10

# Run migrations again
php artisan migrate

# Seed roles
php artisan db:seed --class=RolesSeeder

# Seed admin user (if needed)
php artisan db:seed --class=AdminSeeder
```

## Verification Commands:
```bash
# Check columns in test_results table
php artisan tinker --execute="print_r(\Illuminate\Support\Facades\Schema::getColumnListing('test_results'));"

# Check roles
php artisan tinker --execute="print_r(\Spatie\Permission\Models\Role::all()->toArray());"

# Check users and their roles
php artisan tinker --execute="print_r(\App\Models\User::with('roles')->get()->toArray());"

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```
