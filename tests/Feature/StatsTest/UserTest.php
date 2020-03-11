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
    public function a_user_can_get_a_list_of_the_amount_of_sessions_of_users()
    {
        $this->withoutExceptionHandling();

        $sessions = factory(Session::class, 10)->create(["user" => 15]);

        $response = $this->json("GET", "api/stats/users/login");

        $response
            ->assertStatus(200)
            ->assertJson([
                "counts" => [
                    [
                        "user" => $sessions[0]->user,
                        "total" => 10,
                    ],
                ]
            ]);
    }

    /** @test */
    public function a_user_can_get_a_list_of_all_sessions_belonging_to_a_user()
    {
        $this->withoutExceptionHandling();

        $sessions = factory(Session::class, 2)->create(["user" => 15]);

        $response = $this->json("GET", "api/stats/users/15");

        $sessions = $sessions->map(function ($item, $key) {
            unset($item->user);
            unset($item->updated_at);
            return $item;
        })->toArray();

        $response
            ->assertStatus(200)
            ->assertJson($sessions);
    }
}
