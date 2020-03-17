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

        $actions = factory(Action::class, 2)->create();

        $response = $this->json("GET", "api/actions");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "location" => $actions[0]->location,
                        "action" => $actions[0]->action,
                        "target" => $actions[0]->target,
                        "created_at" => $actions[0]->created_at,
                    ],
                    [
                        "location" => $actions[1]->location,
                        "action" => $actions[1]->action,
                        "target" => $actions[1]->target,
                        "created_at" => $actions[1]->created_at,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_all_filtered_actions()
    {
        $this->withoutExceptionHandling();

        $tmp = [
            [
                "location" => "journey",
                "target" => "save",
            ],
            [
                "location" => "start-learning",
                "target" => "click",
            ],
        ];

        $actions[] = factory(Action::class)->create($tmp[0]);
        $actions[] = factory(Action::class)->create($tmp[1]);

        $response = $this->json("GET", "api/actions?filter[target]={$actions[0]->target}");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "location" => $actions[0]->location,
                        "target" => $actions[0]->target,
                        "created_at" => $actions[0]->created_at,
                    ]
                ],
                "links" => [],
                "meta" => [],
            ])->assertDontSee($actions[1]->target);
    }

    /** @test */
    public function a_user_can_view_all_sessions_with_relations()
    {
        $this->withoutExceptionHandling();

        $actions = factory(Action::class, 2)->create();

        $response = $this->json("GET", "api/actions?include=session,variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "location" => $actions[0]->location,
                        "action" => $actions[0]->action,
                        "target" => $actions[0]->target,
                        "created_at" => $actions[0]->created_at,
                        "session" => [],
                        "variables" => [],
                    ],
                    [
                        "location" => $actions[1]->location,
                        "action" => $actions[1]->action,
                        "target" => $actions[1]->target,
                        "created_at" => $actions[1]->created_at,
                        "session" => [],
                        "variables" => [],
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
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_action_with_its_relations()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        $response = $this->json("GET", "api/actions/{$action->id}?include=session,variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "location" => $action->location,
                "action" => $action->action,
                "target" => $action->target,
                "created_at" => $action->created_at,
                "session" => [],
                "variables" => [],
            ]);
    }

    /** @test */
    public function a_user_can_create_a_new_action()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();

        $data = [
            "location" => $this->faker->randomElement(["Testwordlist", "Shared", "Myprogress", "Login"]),
            "action" => $this->faker->randomElement(["Button press", "Inactivity"]),
            "target" => $this->faker->name,
            "session_id" => $session->id,
        ];

        $response = $this->json("POST", "api/actions", $data);

        $this->assertDatabaseHas("actions", $data);

        unset($data["session_id"]);

        $response
            ->assertStatus(201)
            ->assertJson($data);
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
                    "variable" => "visitor_id",
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
        $variables = factory(Variable::class, 2)->create(["action_id" => $action->id]);

        $response = $this->json("GET", "api/actions/$action->id/variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "variable" => $variables[0]->variable,
                        "value" => $variables[0]->value,
                    ],
                    [
                        "variable" => $variables[1]->variable,
                        "value" => $variables[1]->value,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_actions_filtered_variables()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        $tmp = [
            [
                "variable" => "journey_id",
                "value" => 2,
                "action_id" => $action->id,
            ],
            [
                "variable" => "start-learning_id",
                "value" => 498,
                "action_id" => $action->id,
            ],
        ];

        $variables[] = factory(Variable::class)->create($tmp[0]);
        $variables[] = factory(Variable::class)->create($tmp[1]);

        $response = $this->json("GET", "api/actions/$action->id/variables?filter[value]={$variables[0]->value}");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "variable" => $variables[0]->variable,
                        "value" => $variables[0]->value,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ])->assertDontSee($variables[1]->value);
    }
}
