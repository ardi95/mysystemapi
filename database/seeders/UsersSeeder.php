<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;
use App\Models\Role_user;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $role = new Role();
        $role->name = 'Admin APP';
        $role->save();

        $role2 = new Role();
        $role2->name = 'Admin Kasir';
        $role2->save();

        $admin = new User();
        $admin->name = 'Admin ARAN';
        $admin->email = 'admin@samudera.id';
        $admin->password = bcrypt('samudera123');
        $admin->gender = 'Male';
        $admin->division = 'Divisi 1';
        $admin->birthdate = '1995-04-24';
        $admin->active = 'Yes';
        $admin->save();

        $admin2 = new User();
        $admin2->name = 'Ardi';
        $admin2->email = 'ardi95';
        $admin2->password = bcrypt('samudera123');
        $admin2->gender = 'Male';
        $admin2->division = 'Divisi 1';
        $admin2->birthdate = '1995-04-24';
        $admin2->active = 'Yes';
        $admin2->save();

        $admin3 = new User();
        $admin3->name = 'Heliza';
        $admin3->email = 'heliza16';
        $admin3->password = bcrypt('samudera123');
        $admin3->gender = 'Male';
        $admin3->division = 'Divisi 1';
        $admin3->birthdate = '1995-04-24';
        $admin3->active = 'Yes';
        $admin3->save();

        $admin4 = new User();
        $admin4->name = 'Admin ARAN';
        $admin4->email = 'admin_aran4';
        $admin4->password = bcrypt('samudera123');
        $admin4->gender = 'Male';
        $admin4->division = 'Divisi 1';
        $admin4->birthdate = '1995-04-24';
        $admin4->active = 'Yes';
        $admin4->save();

        $admin5 = new User();
        $admin5->name = 'Admin ARAN';
        $admin5->email = 'admin_aran5';
        $admin5->password = bcrypt('samudera123');
        $admin5->gender = 'Male';
        $admin5->division = 'Divisi 1';
        $admin5->birthdate = '1995-04-24';
        $admin5->active = 'Yes';
        $admin5->save();

        $admin6 = new User();
        $admin6->name = 'Admin ARAN';
        $admin6->email = 'admin_aran6';
        $admin6->password = bcrypt('samudera123');
        $admin6->gender = 'Male';
        $admin6->division = 'Divisi 1';
        $admin6->birthdate = '1995-04-24';
        $admin6->active = 'Yes';
        $admin6->save();

        $admin7 = new User();
        $admin7->name = 'Admin ARAN';
        $admin7->email = 'admin_aran7';
        $admin7->password = bcrypt('samudera123');
        $admin7->gender = 'Male';
        $admin7->division = 'Divisi 1';
        $admin7->birthdate = '1995-04-24';
        $admin7->active = 'Yes';
        $admin7->save();

        $admin8 = new User();
        $admin8->name = 'Admin ARAN';
        $admin8->email = 'admin_aran8';
        $admin8->password = bcrypt('samudera123');
        $admin8->gender = 'Male';
        $admin8->division = 'Divisi 1';
        $admin8->birthdate = '1995-04-24';
        $admin8->active = 'Yes';
        $admin8->save();

        $admin9 = new User();
        $admin9->name = 'Admin ARAN';
        $admin9->email = 'admin_aran9';
        $admin9->password = bcrypt('samudera123');
        $admin9->gender = 'Male';
        $admin9->division = 'Divisi 1';
        $admin9->birthdate = '1995-04-24';
        $admin9->active = 'Yes';
        $admin9->save();

        $admin10 = new User();
        $admin10->name = 'Admin ARAN';
        $admin10->email = 'admin_aran10';
        $admin10->password = bcrypt('samudera123');
        $admin10->gender = 'Male';
        $admin10->division = 'Divisi 1';
        $admin10->birthdate = '1995-04-24';
        $admin10->active = 'Yes';
        $admin10->save();

        $admin11 = new User();
        $admin11->name = 'Admin ARAN';
        $admin11->email = 'admin_aran11';
        $admin11->password = bcrypt('samudera123');
        $admin11->gender = 'Male';
        $admin11->division = 'Divisi 1';
        $admin11->birthdate = '1995-04-24';
        $admin11->active = 'Yes';
        $admin11->save();

        $admin12 = new User();
        $admin12->name = 'Admin ARAN';
        $admin12->email = 'admin_aran12';
        $admin12->password = bcrypt('samudera123');
        $admin12->gender = 'Male';
        $admin12->division = 'Divisi 1';
        $admin12->birthdate = '1995-04-24';
        $admin12->active = 'Yes';
        $admin12->save();
        //$admin->attachRole($adminRole);

        $roleUser = new Role_user();
        $roleUser->role_id = $role->id;
        $roleUser->user_id = $admin->id;
        $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role->id;
        // $roleUser->user_id = $admin2->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin3->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin4->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin5->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin6->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin7->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin8->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin9->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin10->id;
        // $roleUser->save();

        // $roleUser = new Role_user();
        // $roleUser->role_id = $role2->id;
        // $roleUser->user_id = $admin11->id;
        // $roleUser->save();
    }
}
