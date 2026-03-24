<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $adminRole = Role::whereName('admin')->first();
      $userRole = Role::whereName('user')->first();


      $admin_user = User::create(array(
          'name'        => 'admin',
          'email'       => 'admin@zotel.com',
          //'phone'       => '9945300457',
            'password'    => Hash::make('Zotel@1234'),
      ));
      if($admin_user){
          RoleUser::create(array(
              'role_id' => $adminRole->id,
              'user_id' => $admin_user->id,
              'user_type' => $adminRole->name,
          ));
      }

      $user = User::create(array(
          'name'      => 'user',
          'email'     => 'user@automation.com',
          //'phone'       => '9945300458',
          'password'  => Hash::make('123456'),

          //'activated'     => true
      ));
      if($user){
          RoleUser::create(array(
              'role_id' => $userRole->id,
              'user_id' => $user->id,
              'user_type' => $user->name,
          ));
      }
      //User::factory()->count(10)->create();
    }
}
