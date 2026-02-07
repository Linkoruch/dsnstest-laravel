# Виправлення помилки RouteNotFoundException

## Проблема
```
Symfony\Component\Routing\Exception\RouteNotFoundException
Route [test-results.index] not defined.
```

## Причина
У файлі `resources/views/livewire/test-results/manage-attempts.blade.php` використовувалась неправильна назва маршруту `test-results.index`, яка не існувала в `routes/web.php`.

## Рішення

### Було:
```blade
<a href="{{ route('test-results.index') }}" ...>Назад</a>
```

### Стало:
```blade
<a href="{{ route('results.index') }}" ...>Назад</a>
```

## Правильні назви маршрутів у системі

### Маршрути адміністратора:

**Тести:**
- `tests.index` - список тестів
- `tests.create` - створення тесту
- `tests.edit` - редагування тесту
- `tests.manage-attempts` - управління спробами

**Результати:**
- `results.index` - список результатів ✅ (використовуємо цей!)
- `results.view` - перегляд результату

**Користувачі:**
- `users.index` - список користувачів
- `users.create` - створення користувача
- `users.edit` - редагування користувача

### Маршрути користувача:

- `user.tests` - доступні тести
- `user.test.take` - проходження тесту
- `user.results` - історія результатів
- `user.result.view` - перегляд результату

## Виконані дії

1. ✅ Виправлено назву маршруту в `manage-attempts.blade.php`
2. ✅ Очищено кеш views (`php artisan view:clear`)
3. ✅ Видалено скомпільовані views
4. ✅ Очищено всі кеші (`php artisan optimize:clear`)

Помилка виправлена! Тепер посилання "Назад" на сторінці управління спробами працює коректно.

