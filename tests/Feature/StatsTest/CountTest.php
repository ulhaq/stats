<?php

namespace Tests\Feature\StatsTest;

use App\Action;
use App\Variable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_get_a_list_of_locations()
    {
        $this->withoutExceptionHandling();

        $actions = factory(Action::class, 10)->create();

        $response = $this->json("GET", "api/stats/counts");

        $response
            ->assertStatus(200)
            ->assertJson($actions->unique("location")->pluck("location")->toArray());
    }

    /** @test */
    public function a_user_can_get_a_list_of_a_locations_actions()
    {
        $this->withoutExceptionHandling();

        $actions = factory(Action::class, 10)->create();

        $response = $this->json("GET", "api/stats/counts?location={$actions[0]->location}");

        $response
            ->assertStatus(200)
            ->assertJson($actions->unique("action")->where("location", $actions[0]->location)->pluck("action")->toArray());
    }

    /** @test */
    public function a_user_can_get_a_list_of_variables_of_an_action_with_specific_location_and_action()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        $variables = factory(Variable::class, 10)->create(["action_id" => $action->id]);

        $response = $this->json("GET", "api/stats/counts?location={$action->location}&action={$action->action}");

        $response
            ->assertStatus(200)
            ->assertJson($variables->unique("variable")->pluck("variable")->toArray());
    }

    /** @test */
    public function a_user_can_get_counts_filtered_by_location_and_action()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        factory(Variable::class, 2)->create(["action_id" => $action->id]);

        $response = $this->json("POST", "api/stats/counts", [
            "location" => $action->location,
            "action" => $action->action,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(["counts" => 2]);
    }

    /** @test */
    public function a_user_can_get_counts_filtered_by_location_action_and_variables()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        $tmp = [
            [
                "variable" => "journey_id",
                "value" => 2,
                "action_id" => $action->id
            ],
            [
                "variable" => "start-learning_id",
                "value" => 498,
                "action_id" => $action->id
            ],
        ];

        $variables[] = factory(Variable::class)->create($tmp[0]);
        $variables[] = factory(Variable::class)->create($tmp[1]);

        $response = $this->json("POST", "api/stats/counts", [
            "location" => $action->location,
            "action" => $action->action,
            "variables" => [
                $tmp[0]["variable"] => $tmp[0]["value"],
            ],
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(["counts" => 1]);
    }
}
