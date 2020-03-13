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
                "data" => [
                    [
                        "user" => $sessions[0]->user,
                        "total" => 10,
                    ],
                ],
            ]);
    }

    /** @test */
    public function a_user_can_get_a_list_of_the_amount_of_sessions_of_users_who_returned_at_least_x_times_between_dates()
    {
        $this->withoutExceptionHandling();

        $sessions = [
            factory(Session::class, 2)->create(["user" => 1, "created_at" => "2020-03-07 11:00:00"]),
            factory(Session::class, 5)->create(["user" => 2, "created_at" => "2020-03-09 11:00:00"]),
            factory(Session::class, 4)->create(["user" => 3, "created_at" => "2020-03-10 11:00:00"]),
            factory(Session::class, 3)->create(["user" => 4, "created_at" => "2020-03-08 11:00:00"]),
            factory(Session::class, 10)->create(["user" => 5, "created_at" => "2020-03-11 11:00:00"]),
            factory(Session::class, 7)->create(["user" => 6, "created_at" => "2020-03-11 12:00:00"]),
        ];
        
        $response = $this->json("GET", "api/stats/users/login?times=3&from=2020-03-08T08:00&to=2020-03-11T11:59");

        $response
            ->assertStatus(200)
            ->assertJson([
                "current_page" => 1,
                "data" => [
                    [
                        "user" => 5,
                        "total" => 10,
                    ],
                    [
                        "user" => 2,
                        "total" => 5,
                    ],
                    [
                        "user" => 3,
                        "total" => 4,
                    ],
                ],
            ]);
    }

    /** @test */
    public function a_user_can_get_the_percentage_of_users_who_returned_at_least_x_times_between_dates()
    {
        $this->withoutExceptionHandling();

        $sessions = [
            factory(Session::class, 2)->create(["user" => 1, "created_at" => "2020-03-07 11:00:00"]),
            factory(Session::class, 5)->create(["user" => 2, "created_at" => "2020-03-09 11:00:00"]),
            factory(Session::class, 4)->create(["user" => 3, "created_at" => "2020-03-10 11:00:00"]),
            factory(Session::class, 3)->create(["user" => 4, "created_at" => "2020-03-08 11:00:00"]),
            factory(Session::class, 10)->create(["user" => 5, "created_at" => "2020-03-11 11:00:00"]),
            factory(Session::class, 7)->create(["user" => 6, "created_at" => "2020-03-11 12:00:00"]),
        ];

        $response = $this->json("GET", "api/stats/users/returning?times=3&from=2020-03-08T08:00&to=2020-03-11T11:59");

        $totalUsers = sizeof($sessions);
        $totalReturningUsers = 3;

        $percentage = $totalReturningUsers / $totalUsers * 100;

        $response
            ->assertStatus(200)
            ->assertJson([
                "percentage" => $percentage,
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

    /** @test */
    public function a_user_can_get_a_list_of_all_sessions_between_dates_belonging_to_a_user()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create(["user" => 15, "created_at" => "2020-03-08 12:00:00"]);
        unset($session->user);
        unset($session->updated_at);
        factory(Session::class)->create(["user" => 15, "created_at" => "2020-03-12 12:00:00"]);
        

        $response = $this->json("GET", "api/stats/users/15?from=2020-03-08T11:00&to=2020-03-12T11:59");

        $response
            ->assertStatus(200)
            ->assertJson([$session->toArray()])->assertDontSee("2020-03-12");
    }
}
