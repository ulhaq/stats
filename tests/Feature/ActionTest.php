<?php

namespace Tests\Feature;

use App\Action;
use App\Session;
use App\Variable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ActionTest extends TestCase
{
    use DatabaseMigrations, WithFaker;


    /** @test */
    public function a_user_can_view_all_actions()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class, 2)->create();

        $response = $this->json("GET", "api/actions");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "location" => $action[0]->location,
                        "action" => $action[0]->action,
                        "target" => $action[0]->target,
                        "created_at" => $action[0]->created_at,
                        "session" => [
                            "id" => $action[0]->session->id,
                        ],
                    ],
                    [
                        "location" => $action[1]->location,
                        "action" => $action[1]->action,
                        "target" => $action[1]->target,
                        "created_at" => $action[1]->created_at,
                        "session" => [
                            "id" => $action[1]->session->id,
                        ],
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_action()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        $response = $this->json("GET", "api/actions/$action->id");

        $response
            ->assertStatus(200)
            ->assertJson([
                "location" => $action->location,
                "action" => $action->action,
                "target" => $action->target,
                "created_at" => $action->created_at,
                "session" => [
                    "id" => $action->session->id,
                ],
            ]);
    }

    /** @test */
    public function a_user_can_create_a_new_action_with_variables()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();

        $data = [
            "location" => $this->faker->randomElement(["Testwordlist", "Shared", "Myprogress", "Login"]),
            "action" => $this->faker->randomElement(["Button press", "Inactivity"]),
            "target" => $this->faker->name,
            "session_id" => $session->id,
            "variables" => [
                [
                    "variable" => "user_id",
                    "value" => 5,
                ],
                [
                    "variable" => "journey_id",
                    "value" => 2,
                ],
            ],
        ];

        $response = $this->json("POST", "api/actions", $data);

        $this->assertDatabaseHas("actions", Arr::except($data, ["variables"]));

        unset($data["session_id"]);

        $response
            ->assertStatus(201)
            ->assertJson($data);
    }

    /** @test */
    public function a_user_can_delete_an_action()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        $response = $this->json("DELETE", "api/actions/$action->id");

        $this->assertDatabaseMissing("actions", [
            "location" => $action->location,
            "action" => $action->action,
            "target" => $action->target,
            "created_at" => $action->created_at,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "location" => $action->location,
                "action" => $action->action,
                "target" => $action->target,
                "created_at" => $action->created_at,
            ]);
    }


    /** @test */
    public function a_user_can_view_a_specific_actions_variables()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();
        $variable = factory(Variable::class, 2)->create(["action_id" => $action->id]);

        $response = $this->json("GET", "api/actions/$action->id/variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        'variable' => $variable[0]->variable,
                        'value' => $variable[0]->value,
                    ],
                    [
                        'variable' => $variable[1]->variable,
                        'value' => $variable[1]->value,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }
}
