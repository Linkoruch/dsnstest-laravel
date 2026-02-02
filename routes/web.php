<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    // Маршрути для тестів (тільки для адміністраторів)
    Route::prefix('tests')->name('tests.')->group(function () {
        Route::get('/', \App\Livewire\Tests\TestList::class)->name('index');
        Route::get('/create', \App\Livewire\Tests\CreateTest::class)->name('create');
        Route::get('/{test}/edit', \App\Livewire\Tests\EditTest::class)->name('edit');
    });

    // Маршрути для результатів тестів (тільки для адміністраторів)
    Route::prefix('results')->name('results.')->group(function () {
        Route::get('/', \App\Livewire\TestResults\TestResultList::class)->name('index');
        Route::get('/{testResult}', \App\Livewire\TestResults\ViewTestResult::class)->name('view');
    });

    // Маршрути для користувачів (тільки для адміністраторів)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', \App\Livewire\Users\UserList::class)->name('index');
        Route::get('/create', \App\Livewire\Users\CreateUser::class)->name('create');
        Route::get('/{user}/edit', \App\Livewire\Users\EditUser::class)->name('edit');
    });
});


/**
 * Тут мають бути ваші додаткові маршрути
 *
 * Всі роути мають визивати функцію із Controller або Livewire компоненту по типу
 *
 * В Controller ми тільки опрацьовуємо логіку, а віддаємо в'юху або редірект
 *
 * Вся маштабування і робота з даними має відбуватись в Livewire компонентах, або в сервісах
 *
 * Для оновлення бази даних використовуємо команди "php artisan migrate"
 *
 * Проект в собі буде використувавути tailwind (для стилів) , Livewire, Laravel Permission
 *
 * ✅ DONE - Створити ролі користувачів (admin, user)
 * ✅ DONE - Створити модель + міграцію + CRUD для тестів (назва, опис, шлях до json файлу з питаннями)
 * ✅ DONE - Створити модель + міграцію + CRUD для результатів тестів (який користувач, який тест, шлях до json файлу result, дата)
 * ✅ DONE - Тип файлу з питаннями має бути у форматі {
 *    "questions": [
 *       {"question_id": 1, "question_text": "What is 2+2?", "options": {"A": "3", "B": "4", "C": "5", "D": "6"}, "correct_answer": "B"},
 *      {"question_id": 2, "question_text": "What is the capital of France?", "options": {"A": "Berlin", "B": "Madrid", "C": "Paris", "D": "Rome"}, "correct_answer": "C"},
 *     ...
 *   ]}
 * ✅ DONE - Тип файлу відповіді має бути у форматі {
 *     "correct_answers": 7,
 *     "total_questions": 10,
 *     "answers": [
 *         {"question_id": 1, "given_answer": "A", "is_correct": true},
 *        {"question_id": 2, "given_answer": "C", "is_correct": false},
 *       ...
 *   ]}
 * }
 * ✅ DONE - Переробитити dashboard на список всіх тестів з можливістю пошуку
 * ✅ DONE - Додати можливість для адміністратора створювати, редагувати, видаляти тести
 *  - Це має бути Окремий шлях в роутах /tests
 *  - Також це має бути окремий Livewire компонент
 *  - Для редагування тесту треба клікнути на значок олівця біля тесту
 *  - Для додавання тесту треба клікнути на кнопку "Додати тест"
 *  - Для видалення тесту треба клікнути на значок корзини біля тесту (зробити це через модальне вікно підтвердження)
 *
 */

// Маршрути для користувачів (проходження тестів)
Route::middleware(['auth'])->prefix('my')->name('user.')->group(function () {
    Route::get('/tests', \App\Livewire\UserTests\AvailableTests::class)->name('tests');
    Route::get('/test/{test}', \App\Livewire\UserTests\TakeTest::class)->name('test.take');
    Route::get('/results', \App\Livewire\UserTests\MyResults::class)->name('results');
    Route::get('/result/{testResult}', \App\Livewire\UserTests\ViewMyResult::class)->name('result.view');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
