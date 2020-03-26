<?php

namespace Tests\Feature\StatsTest;

use App\Session;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VisitorTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_get_a_list_of_the_amount_of_sessions_of_visitors()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $sessions = factory(Session::class, 10)->create(["visitor" => 15]);

        $response = $this->json("GET", "api/stats/visitors/visits");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "visitor" => $sessions[0]->visitor,
                        "total" => 10,
                    ],
                ],
            ]);
    }

    /** @test */
    public function a_user_can_get_a_list_of_the_amount_of_sessions_of_visitors_who_returned_at_least_x_times_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $sessions = [
            factory(Session::class, 2)->create(["visitor" => 1, "created_at" => "2020-03-07 11:00:00"]),
            factory(Session::class, 5)->create(["visitor" => 2, "created_at" => "2020-03-09 11:00:00"]),
            factory(Session::class, 4)->create(["visitor" => 3, "created_at" => "2020-03-10 11:00:00"]),
            factory(Session::class, 3)->create(["visitor" => 4, "created_at" => "2020-03-08 11:00:00"]),
            factory(Session::class, 10)->create(["visitor" => 5, "created_at" => "2020-03-11 11:00:00"]),
            factory(Session::class, 7)->create(["visitor" => 6, "created_at" => "2020-03-11 12:00:00"]),
        ];

        $response = $this->json("GET", "api/stats/visitors/visits?times=3&from=2020-03-08T08:00&to=2020-03-11T11:59");

        $response
            ->assertStatus(200)
            ->assertJson([
                "current_page" => 1,
                "data" => [
                    [
                        "visitor" => 5,
                        "total" => 10,
                    ],
                    [
                        "visitor" => 2,
                        "total" => 5,
                    ],
                    [
                        "visitor" => 3,
                        "total" => 4,
                    ],
                ],
            ]);
    }

    /** @test */
    public function a_user_can_get_the_percentage_of_visitors_who_returned_at_least_x_times_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $sessions = [
            factory(Session::class, 2)->create(["visitor" => 1, "created_at" => "2020-03-07 11:00:00"]),
            factory(Session::class, 5)->create(["visitor" => 2, "created_at" => "2020-03-09 11:00:00"]),
            factory(Session::class, 4)->create(["visitor" => 3, "created_at" => "2020-03-10 11:00:00"]),
            factory(Session::class, 3)->create(["visitor" => 4, "created_at" => "2020-03-08 11:00:00"]),
            factory(Session::class, 10)->create(["visitor" => 5, "created_at" => "2020-03-11 11:00:00"]),
            factory(Session::class, 7)->create(["visitor" => 6, "created_at" => "2020-03-11 12:00:00"]),
        ];

        $response = $this->json("GET", "api/stats/visitors/returning?times=3&from=2020-03-08T08:00&to=2020-03-11T11:59");

        $totalVisitors = sizeof($sessions);
        $totalReturningVisitors = 3;

        $percentage = $totalReturningVisitors / $totalVisitors * 100;

        $response
            ->assertStatus(200)
            ->assertJson([
                "percentage" => $percentage,
            ]);
    }

    /** @test */
    public function a_user_can_get_a_list_of_all_sessions_belonging_to_a_visitor()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $sessions = factory(Session::class, 2)->create(["visitor" => 15]);

        $response = $this->json("GET", "api/stats/visitors/15");

        $sessions = $sessions->map(function ($item, $key) {
            unset($item->visitor);
            unset($item->updated_at);
            return $item;
        })->toArray();

        $response
            ->assertStatus(200)
            ->assertJson([
                "current_page" => 1,
                "data" => $sessions,
            ]);
    }

    /** @test */
    public function a_user_can_get_a_list_of_all_sessions_between_dates_belonging_to_a_visitor()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $session = factory(Session::class)->create(["visitor" => 15, "client" => "Unity", "platform" => "Windows", "created_at" => "2020-03-08 12:00:00"]);
        unset($session->visitor);
        unset($session->updated_at);
        $missing = factory(Session::class)->create(["visitor" => 15, "client" => "Browser", "platform" => "Android", "created_at" => "2020-03-12 12:00:00"]);


        $response = $this->json("GET", "api/stats/visitors/15?from=2020-03-08T11:00&to=2020-03-12T11:59");

        $response
            ->assertStatus(200)
            ->assertJson([
                "current_page" => 1,
                "data" => [
                    $session->toArray(),
                ],
            ])->assertJsonMissing($missing->toArray());
    }
}
