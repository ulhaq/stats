<?php

namespace Tests\Feature\StatsTest;

use App\Action;
use App\Session;
use App\Variable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_get_view_counts_of_a_journey()
    {
        $this->withoutExceptionHandling();

        $id = 1;

        $session = factory(Session::class)->create();
        for ($i = 0; $i < 10; $i++) {
            $action = Action::create([
                'location' => "journey",
                'action' => "view",
                'target' => $this->faker->name,
                'session_id' => $session->id,
            ]);
            Variable::create([
                'variable' => "user_id",
                'value' => rand(),
                'action_id' => $action->id,
            ]);
            Variable::create([
                'variable' => "journey_id",
                'value' => $id,
                'action_id' => $action->id,
            ]);
        }

        $response = $this->json("GET", "api/stats/counts/journey/$id/view");

        $response
            ->assertStatus(200)
            ->assertJson(['counts' => 10]);
    }

    /** @test */
    public function a_user_can_get_counts_of_a_journeys_addreview()
    {
        $this->withoutExceptionHandling();

        $id = 1;

        $session = factory(Session::class)->create();
        for ($i = 0; $i < 10; $i++) {
            $action = Action::create([
                'location' => "journey",
                'action' => "addreview",
                'target' => $this->faker->name,
                'session_id' => $session->id,
            ]);
            Variable::create([
                'variable' => "user_id",
                'value' => rand(),
                'action_id' => $action->id,
            ]);
            Variable::create([
                'variable' => "journey_id",
                'value' => $id,
                'action_id' => $action->id,
            ]);
        }

        $response = $this->json("GET", "api/stats/counts/journey/$id/addreview");

        $response
            ->assertStatus(200)
            ->assertJson(['counts' => 10]);
    }

    /** @test */
    public function a_user_can_get_counts_of_a_journeys_share()
    {
        $this->withoutExceptionHandling();

        $id = 1;

        $session = factory(Session::class)->create();
        for ($i = 0; $i < 10; $i++) {
            $action = Action::create([
                'location' => "journey",
                'action' => "share",
                'target' => $this->faker->name,
                'session_id' => $session->id,
            ]);
            Variable::create([
                'variable' => "user_id",
                'value' => rand(),
                'action_id' => $action->id,
            ]);
            Variable::create([
                'variable' => "journey_id",
                'value' => $id,
                'action_id' => $action->id,
            ]);
        }

        $response = $this->json("GET", "api/stats/counts/journey/$id/share");

        $response
            ->assertStatus(200)
            ->assertJson(['counts' => 10]);
    }

    /** @test */
    public function a_user_can_get_counts_of_a_journeys_save()
    {
        $this->withoutExceptionHandling();

        $id = 1;

        $session = factory(Session::class)->create();
        for ($i = 0; $i < 10; $i++) {
            $action = Action::create([
                'location' => "journey",
                'action' => "save",
                'target' => $this->faker->name,
                'session_id' => $session->id,
            ]);
            Variable::create([
                'variable' => "user_id",
                'value' => rand(),
                'action_id' => $action->id,
            ]);
            Variable::create([
                'variable' => "journey_id",
                'value' => $id,
                'action_id' => $action->id,
            ]);
        }

        $response = $this->json("GET", "api/stats/counts/journey/$id/save");

        $response
            ->assertStatus(200)
            ->assertJson(['counts' => 10]);
    }
}
