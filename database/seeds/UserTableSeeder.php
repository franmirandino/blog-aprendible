<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();
        Role::truncate();
        User::truncate();

        $adminRole = Role::create(['name' => 'Admin', 'display_name' => 'Administrador']);
        $writerRole = Role::create(['name' => 'Writer', 'display_name' => 'Escritor']);

        $viewPostsPermission = Permission::create(['name' => 'View posts', 'display_name' => 'Ver publicaciones']);
        $reatePostsPermission = Permission::create(['name' => 'Create posts', 'display_name' => 'Crear publicaciones']);
        $updatePostsPermission = Permission::create(['name' => 'Update posts', 'display_name' => 'Actualizar publicaciones']);
        $deletePostsPermission = Permission::create(['name' => 'Delete posts', 'display_name' => 'Borrar publicaciones']);

        $viewUsersPermission = Permission::create(['name' => 'View users', 'display_name' => 'Ver usuarios']);
        $reateUsersPermission = Permission::create(['name' => 'Create users', 'display_name' => 'Crear usuarios']);
        $updateUsersPermission = Permission::create(['name' => 'Update users', 'display_name' => 'Actualizar usuarios']);
        $deleteUsersPermission = Permission::create(['name' => 'Delete users', 'display_name' => 'Borrar usuarios']);

        $viewRolesPermission = Permission::create(['name' => 'View roles', 'display_name' => 'Ver roles']);
        $reateRolesPermission = Permission::create(['name' => 'Create roles', 'display_name' => 'Crear roles']);
        $updateRolesPermission = Permission::create(['name' => 'Update roles', 'display_name' => 'Actualizar roles']);
        $deleteRolesPermission = Permission::create(['name' => 'Delete roles', 'display_name' => 'Borrar roles']);

        $viewPermissionsPermission = Permission::create(['name' => 'View permissions', 'display_name' => 'Ver permisos']);

        $updatePermissionsPermission = Permission::create(['name' => 'Update permissions', 'display_name' => 'Actualizar permisos']);



        $admin           = new User;
        $admin->name     = 'Francisco Miranda';
        $admin->email    = 'fmiranda@deuce.com';
        $admin->password = '123123';
        $admin->save();

        $admin->assignRole($adminRole);

        $writer           = new User;
        $writer->name     = 'Miranda';
        $writer->email    = 'fmiranda@gmail.com';
        $writer->password = '123123';
        $writer->save();

        $writer->assignRole($writerRole);


    }
}
