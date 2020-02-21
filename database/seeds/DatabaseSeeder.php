<?php

use App\Action;
use App\Session;
use App\Variable;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(Session::class, 10)->create()->each(function ($session) {
            factory(Action::class, 5)->create(['session_id' => $session->id])->each(function ($action) {
                factory(Variable::class, 2)->create(['action_id' => $action->id]);
            });
        });
    }
}
