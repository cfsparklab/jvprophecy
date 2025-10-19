<?php
/**
 * User Roles Checker
 * 
 * Upload this to production and run: php check-user-roles.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== User Roles Report ===\n\n";

$users = \App\Models\User::with('roles')->get();

if ($users->isEmpty()) {
    echo "❌ No users found in database\n";
    exit(1);
}

echo "Total Users: " . $users->count() . "\n\n";

foreach ($users as $user) {
    $roleNames = $user->roles->pluck('name')->toArray();
    $roleDisplay = $user->roles->pluck('display_name')->toArray();
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "👤 User: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Roles in DB: " . (empty($roleNames) ? '(none)' : implode(', ', $roleNames)) . "\n";
    echo "   Display Names: " . (empty($roleDisplay) ? '(none)' : implode(', ', $roleDisplay)) . "\n";
    echo "   Primary Role: {$user->primary_role}\n";
    
    // Check role assignments
    $userRoles = \DB::table('user_roles')
        ->where('user_id', $user->id)
        ->join('roles', 'user_roles.role_id', '=', 'roles.id')
        ->select('roles.name', 'roles.display_name')
        ->get();
    
    if ($userRoles->isEmpty()) {
        echo "   ⚠️  No roles assigned in user_roles table!\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "=== Available Roles ===\n\n";
$roles = \App\Models\Role::all();

if ($roles->isEmpty()) {
    echo "❌ No roles found in database!\n";
} else {
    foreach ($roles as $role) {
        $userCount = \DB::table('user_roles')->where('role_id', $role->id)->count();
        echo "• {$role->name}\n";
        echo "  Display: {$role->display_name}\n";
        echo "  Users assigned: {$userCount}\n\n";
    }
}

echo "=== Diagnosis ===\n\n";

// Check if all users have super_admin role
$superAdminCount = \DB::table('user_roles')
    ->join('roles', 'user_roles.role_id', '=', 'roles.id')
    ->where('roles.name', 'super_admin')
    ->count();

if ($superAdminCount == $users->count() && $users->count() > 1) {
    echo "⚠️  WARNING: All {$users->count()} users have 'super_admin' role!\n";
    echo "   This is why everyone shows as 'Super Administrator'\n\n";
    echo "🔧 Solution:\n";
    echo "   Run this SQL to fix regular user roles:\n\n";
    echo "   UPDATE user_roles SET role_id = (\n";
    echo "       SELECT id FROM roles WHERE name = 'user' LIMIT 1\n";
    echo "   ) WHERE user_id IN (\n";
    echo "       SELECT id FROM users WHERE email NOT LIKE '%@jvprophecy.com'\n";
    echo "   );\n\n";
} else {
    echo "✅ Role distribution looks normal\n";
}

// Check for users without roles
$usersWithoutRoles = \DB::table('users')
    ->leftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')
    ->whereNull('user_roles.user_id')
    ->count();

if ($usersWithoutRoles > 0) {
    echo "\n⚠️  {$usersWithoutRoles} users have NO roles assigned!\n";
    echo "   They will show as 'Member' by default\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

