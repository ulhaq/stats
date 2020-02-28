<?php

namespace Tests\Feature\StatsTest;

use App\Session;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_get_a_list_of_how_many_times_users_have_logged_in()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class, 10)->create(["user" => 15]);

        $response = $this->json("GET", "api/stats/users/login");

        $response
            ->assertStatus(200)
            ->assertJson([
                "counts" => [
                    [
                        "user" => $session[0]->user,
                        "total" => 10,
                    ],
                ]
            ]);
    }
}
