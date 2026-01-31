Instruction
Configure project settings in .env file
```
cp .env.example .env
```

```
composer install
```

```
php artisan key:generate
```

```
npm install && npm run build
```

Set up database and run migrations
```
php artisan migrate
```

**Add new administrator**
Change data in .env file

ADMIN_NAME="New Admin Name"
ADMIN_EMAIL=""
ADMIN_PASSWORD=""

```
php artisan optimize:clear
```

```
php artisan db:seed --class=AdminSeeder
```


**Run the development server**
```
php artisan serve
```
```
npm run dev
```
# dsnstest-laravel
