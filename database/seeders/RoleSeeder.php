<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'coordinator']);
        Role::create(['name' => 'klant']);
        Role::create(['name' => 'lid']);
        Role::create(['name' => 'penningmeester']);
        Role::create(['name' => 'secretaris']);
    }
}
