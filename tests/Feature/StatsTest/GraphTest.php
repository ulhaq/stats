<?php

namespace Tests\Feature\StatsTest;

use App\Session;
use App\Action;
use App\Variable;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GraphTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_get_daily_counts_for_sessions_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Session::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-04-01 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-05-23 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2019-02-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=session&occurrence=%D %M %Y&from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "9th March 2020" => 2,
                "1st April 2020" => 1,
                "23rd May 2020" => 1,
            ]);
    }

    /** @test */
    public function a_user_can_get_monthly_counts_for_sessions_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Session::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-03-25 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-04-01 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-05-23 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2019-02-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=session&occurrence=%M&year=2020&from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "March" => 2,
                "April" => 1,
                "May" => 1,
            ]);
    }

    /** @test */
    public function a_user_can_get_annually_counts_for_sessions_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Session::class)->create(["created_at" => "2018-04-01 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2019-02-23 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2019-08-19 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Session::class)->create(["created_at" => "2020-03-25 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=session&occurrence=%Y&from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "2019" => 1,
                "2020" => 2,
            ]);
    }

    /** @test */
    public function a_user_can_get_daily_counts_for_actions_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Action::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-04-01 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-05-23 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2019-02-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=action&occurrence=%D %M %Y&from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "9th March 2020" => 2,
                "1st April 2020" => 1,
                "23rd May 2020" => 1,
            ]);
    }

    /** @test */
    public function a_user_can_get_monthly_counts_for_actions_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Action::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-03-25 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-04-01 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-05-23 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2019-02-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=action&occurrence=%M&year=2020&from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "March" => 2,
                "April" => 1,
                "May" => 1,
            ]);
    }

    /** @test */
    public function a_user_can_get_annually_counts_for_actions_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Action::class)->create(["created_at" => "2018-04-01 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2019-02-23 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2019-08-19 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Action::class)->create(["created_at" => "2020-03-25 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=action&occurrence=%Y&from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "2019" => 1,
                "2020" => 2,
            ]);
    }

    /** @test */
    public function a_user_can_get_daily_counts_for_variables_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Variable::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2020-04-01 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2020-05-23 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2019-02-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=variable&occurrence=%D %M %Y&from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "9th March 2020" => 2,
                "1st April 2020" => 1,
                "23rd May 2020" => 1,
            ]);
    }

    /** @test */
    public function a_user_can_get_monthly_counts_for_variables_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Variable::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2020-03-25 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2020-04-01 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2020-05-23 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2019-02-19 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=variable&occurrence=%M&year=2020&from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "March" => 2,
                "April" => 1,
                "May" => 1,
            ]);
    }

    /** @test */
    public function a_user_can_get_annually_counts_for_variables_between_dates()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Variable::class)->create(["created_at" => "2018-04-01 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2019-02-23 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2019-08-19 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2020-03-09 15:00:00"]);
        factory(Variable::class)->create(["created_at" => "2020-03-25 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs?target=variable&occurrence=%Y&from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "2019" => 1,
                "2020" => 2,
            ]);
    }

    /** @test */
    public function a_user_can_get_counts_for_users_origins()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        factory(Session::class)->create(["origin" => "Norway", "created_at" => "2018-04-01 15:00:00"]);
        factory(Session::class)->create(["origin" => "Pakistan", "created_at" => "2019-05-23 15:00:00"]);
        factory(Session::class)->create(["origin" => "Pakistan", "created_at" => "2019-08-19 15:00:00"]);
        factory(Session::class)->create(["origin" => "Sweden", "created_at" => "2020-03-09 15:00:00"]);
        factory(Session::class)->create(["origin" => "Denmark", "created_at" => "2020-03-25 15:00:00"]);

        $response = $this->json("GET", "api/stats/graphs/users/origins?from=2019-03-09T18:00&to=2020-12-03T18:00");

        $response
            ->assertStatus(200)
            ->assertJson([
                "Pakistan" => 2,
                "Sweden" => 1,
                "Denmark" => 1,
            ]);
    }
}
