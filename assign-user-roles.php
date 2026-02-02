<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::all();
$assigned = 0;

foreach ($users as $user) {
    if (!$user->hasRole('admin') && !$user->hasRole('user')) {
        $user->assignRole('user');
        echo "✅ Assigned 'user' role to: {$user->email}\n";
        $assigned++;
    } else {
        echo "ℹ️  {$user->email} already has roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
    }
}

echo "\n";
echo "========================================\n";
echo "Total users assigned 'user' role: {$assigned}\n";
echo "========================================\n";
