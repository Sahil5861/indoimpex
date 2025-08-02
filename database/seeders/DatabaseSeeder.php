<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::findByName('admin');
        $dealer = Role::findByName('dealer');
        $user = Role::findByName('user');

        $admin->givePermissionTo(['manage-users', 'manage-products', 'view-dashboard']);
        $dealer->givePermissionTo(['view-dashboard']);
        $user->givePermissionTo(['view-dashboard']);

    }
}
