<?php

namespace Tests\Feature;

use App\Action;
use App\Session;
use App\Variable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
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
                    ],
                    [
                        "location" => $action[1]->location,
                        "action" => $action[1]->action,
                        "target" => $action[1]->target,
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
            ]);
    }

    /** @test */
    public function a_user_can_create_a_new_action()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();

        $data = [
            "location" =>  $this->faker->randomElement(["Testwordlist", "Shared", "Myprogress", "Login"]),
            "action" =>  $this->faker->randomElement(["Button press", "Inactivity"]),
            "target" =>  $this->faker->name,
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
    public function a_user_can_delete_an_action()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        $response = $this->json("DELETE", "api/actions/$action->id");

        $this->assertDatabaseMissing("actions", [
            "location" => $action->location,
            "action" => $action->action,
            "target" => $action->target,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "location" => $action->location,
                "action" => $action->action,
                "target" => $action->target,
            ]);
    }


    /** @test */
    public function a_user_can_view_a_specific_actions_variables()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();
        $variable = factory(Variable::class)->create(["action_id" => $action->id]);

        $response = $this->json("GET", "api/actions/$action->id/variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        'variable' => $variable->variable,
                        'value' => $variable->value,
                        'action' => [],
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }
}
