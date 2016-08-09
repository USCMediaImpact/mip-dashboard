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

        DB::table('roles')->insert([
            'name' => 'SuperAdmin'
        ]);

        DB::table('roles')->insert([
            'name' => 'Admin'
        ]);

        $users = [
            ['Alex Schaffert', 'aschaffert@scpr.org', 'mipdashboard15'],
            ['Brian Vasallo', 'bvasallo@scpr.org', 'mipdashboard15'],
            ['Sean Dillingham', 'sdillingham@scpr.org', 'mipdashboard15'],
            ['Vijay Singh', 'vsingh@scpr.org', 'mipdashboard15'],
        ];

        foreach($users as $row)
        DB::table('users')->insert([
            'name' => $row[0],
            'email' => $row[1],
            'password' => bcrypt($row[2]),
            'client_id' => 1
        ]);
        
//        $user = App\Models\User::where('email', '=', 'steve.yin@mediaimpactproject.org')->first();
//        $role = App\Models\Role::where('name', '=', 'SuperAdmin')->first();
//        $user->roles()->attach($role);
//        $user->save();

        Model::reguard();
    }
}
