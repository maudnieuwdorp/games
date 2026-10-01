<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $klant = Role::firstOrCreate(['name' => 'klant', 'guard_name' => 'web']);

        // Permissies aanmaken
        $permissions = [
            'game bekijken',
            'game invoeren',
            'game bewerken',
            'game verwijderen',
        ];

        $permissionModels = array_map(static function (string $name): Permission {
            return Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }, $permissions);

        $admin->syncPermissions($permissionModels);
        $klant->syncPermissions(['game bekijken']);

        User::doesntHave('roles')->each(static function (User $user) use ($klant): void {
            $user->assignRole($klant);
        });
    }
}
