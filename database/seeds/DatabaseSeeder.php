<?php

use App\Action;
use App\Session;
use App\Variable;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        factory(Session::class, 10)->create()->each(function ($session) {
            factory(Action::class, 5)->create(['session_id' => $session->id])->each(function ($action) {
                factory(Variable::class, 2)->create(['action_id' => $action->id]);
            });
        });
    }
}
