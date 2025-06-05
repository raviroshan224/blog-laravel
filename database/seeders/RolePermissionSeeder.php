<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        Permission::create(['name' => 'post-create']);
        Permission::create(['name' => 'post-edit']);
        Permission::create(['name' => 'post-delete']);
        Permission::create(['name' => 'category-manage']);
        Permission::create(['name' => 'user-manage']);

        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $editor = Role::create(['name' => 'editor']);

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());
        $editor->givePermissionTo(['post-create', 'post-edit']);
    }
}
