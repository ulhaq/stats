<?php

namespace Tests\Feature\StatsTest;

use App\Action;
use App\Session;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Airlock\Airlock;
use Tests\TestCase;

class GraphTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_get_monthly_counts_for_sessions()
    {
        $this->withoutExceptionHandling();

        Airlock::actingAs(factory(User::class)->make());

        factory(Session::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-03-25 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-04-01 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-05-23 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-07-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=session&occurrence=M&year=2020");

        $response
            ->assertStatus(200)
            ->assertJson([
                "March" => 2,
                "April" => 1,
                "May" => 1,
                "July" => 1,
            ]);
    }

    /** @test */
    public function a_user_can_get_annually_counts_for_sessions()
    {
        $this->withoutExceptionHandling();

        Airlock::actingAs(factory(User::class)->make());

        factory(Session::class)->create(["created_at" => "2017-03-09 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2017-03-25 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2018-04-01 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2019-05-23 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-07-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=session&occurrence=Y");

        $response
            ->assertStatus(200)
            ->assertJson([
                "2017" => 2,
                "2018" => 1,
                "2019" => 1,
                "2020" => 1,
            ]);
    }

    /** @test */
    public function a_user_can_get_monthly_counts_for_actions()
    {
        $this->withoutExceptionHandling();

        Airlock::actingAs(factory(User::class)->make());

        factory(Action::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-03-25 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-04-01 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-05-23 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-07-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=action&occurrence=M&year=2020");

        $response
            ->assertStatus(200)
            ->assertJson([
                "March" => 2,
                "April" => 1,
                "May" => 1,
                "July" => 1,
            ]);
    }

    /** @test */
    public function a_user_can_get_annually_counts_for_actions()
    {
        $this->withoutExceptionHandling();

        Airlock::actingAs(factory(User::class)->make());

        factory(Action::class)->create(["created_at" => "2017-03-09 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2017-03-25 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2018-04-01 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2019-05-23 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-07-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=action&occurrence=Y");

        $response
            ->assertStatus(200)
            ->assertJson([
                "2017" => 2,
                "2018" => 1,
                "2019" => 1,
                "2020" => 1,
            ]);
    }
}
