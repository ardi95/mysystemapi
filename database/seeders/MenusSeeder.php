<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Menu;
use App\Models\Role;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::find(1);
        $role2 = Role::find(2);

        $menu = Menu::create([
            'key_name' => 'appmanagement',
            'name' => 'App Management',
            'url' => '',
            'order_number' => 1,
        ]);

        $menu11 = Menu::create([
            'key_name' => 'users',
            'name' => 'Users',
            'url' => '/app-management/users',
            'order_number' => 1,
            'parent_menu_id' => $menu->id
        ]);

        $menu12 = Menu::create([
            'key_name' => 'menu',
            'name' => 'Menu',
            'url' => '/app-management/menus',
            'order_number' => 2,
            'parent_menu_id' => $menu->id
        ]);

        $menu13 = Menu::create([
            'key_name' => 'role',
            'name' => 'Role',
            'url' => '/app-management/roles',
            'order_number' => 3,
            'parent_menu_id' => $menu->id
        ]);

        $menu14 = Menu::create([
            'key_name' => 'rolemenu',
            'name' => 'Role Menu',
            'url' => '/app-management/role-menu',
            'order_number' => 4,
            'parent_menu_id' => $menu->id
        ]);

        $role1->menus()->attach($menu->id, ['permission' => 'access']);

        $role1->menus()->attach($menu11->id, ['permission' => 'access']);
        $role1->menus()->attach($menu11->id, ['permission' => 'create']);
        $role1->menus()->attach($menu11->id, ['permission' => 'update']);
        $role1->menus()->attach($menu11->id, ['permission' => 'delete']);

        $role1->menus()->attach($menu12->id, ['permission' => 'access']);
        $role1->menus()->attach($menu12->id, ['permission' => 'create']);
        $role1->menus()->attach($menu12->id, ['permission' => 'update']);
        $role1->menus()->attach($menu12->id, ['permission' => 'delete']);

        $role1->menus()->attach($menu13->id, ['permission' => 'access']);
        $role1->menus()->attach($menu13->id, ['permission' => 'create']);
        $role1->menus()->attach($menu13->id, ['permission' => 'update']);
        $role1->menus()->attach($menu13->id, ['permission' => 'delete']);

        $role1->menus()->attach($menu14->id, ['permission' => 'access']);
        $role1->menus()->attach($menu14->id, ['permission' => 'create']);
        $role1->menus()->attach($menu14->id, ['permission' => 'update']);
        $role1->menus()->attach($menu14->id, ['permission' => 'delete']);

        $role2->menus()->attach($menu->id, ['permission' => 'access']);
        $role2->menus()->attach($menu13->id, ['permission' => 'access']);
    }
}
