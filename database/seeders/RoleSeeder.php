<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $admin = Role::create([
          'name' => 'admin',
          'display_name' => 'Admin', // optional
          'description' => 'User is the owner of a given project', // optional
      ]);

      $user = Role::create([
          'name' => 'user',
          'display_name' => 'user ', // optional
          'description' => 'User is not allowed to manage and edit other users', // optional
      ]);
      
    }
}
