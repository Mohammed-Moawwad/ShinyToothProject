<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Patient;
use Illuminate\Support\Facades\Hash;

// Create admin patient
$admin = Patient::create([
    'name' => 'Admin User',
    'email' => 'admin@shinytooth.com',
    'password' => Hash::make('Admin_123'),
    'phone' => '555-0000',
]);

echo "✅ Admin account created successfully!\n";
echo "Email: admin@shinytooth.com\n";
echo "Password: Admin_123\n";
echo "ID: " . $admin->id . "\n";
?>
