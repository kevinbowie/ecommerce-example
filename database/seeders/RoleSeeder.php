<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'Super Admin',
            'Content Writer',
            'Editor',
        ];
        foreach ($roles as $role) {
            Role::create([
                'name' => $role
            ]);
        }

        $superAdmin = Role::where("name", 'Super Admin')->first();
        User::find(1)->assignRole($superAdmin);

        $editor = Role::where('name', 'Editor')->first();
        User::find(2)->assignRole($editor);

        Permission::create(['name' => 'view permissions module']);
        Permission::create(['name' => 'view permissions list']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view roles module']);
        Permission::create(['name' => 'view roles list']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view users module']);
        Permission::create(['name' => 'view users list']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'view categories module']);
        Permission::create(['name' => 'view categories list']);
        Permission::create(['name' => 'create category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);
    }
}
