<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('users')->insert([
            'name' => 'steve.yin',
            'email' => 'steve.yin@mediaimpactproject.org',
            'password' => bcrypt('admin123!@#'),
        ]);

        DB::table('roles')->insert([
            'name' => 'SuperUser'
        ]);

        DB::table('roles')->insert([
            'name' => 'Admin'
        ]);

        $user = App\User::where('email', '=', 'steve.yin@mediaimpactproject.org')->first();
        $role = App\Role::where('name', '=', 'SuperUser')->first();
        $user->roles()->attach($role);
        $user->save();

        Model::reguard();
    }
}
