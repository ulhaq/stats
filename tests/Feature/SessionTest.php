<?php

namespace Tests\Feature;

use App\Action;
use App\Session;
use App\User;
use App\Variable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SessionTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_view_all_sessions()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $sessions = factory(Session::class, 2)->create(["origin" => $this->faker->country]);

        $response = $this->json("GET", "api/sessions");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "visitor" => $sessions[0]->visitor,
                        "client" => $sessions[0]->client,
                        "platform" => $sessions[0]->platform,
                        "origin" => $sessions[0]->origin,
                        "created_at" => $sessions[0]->created_at,
                    ],
                    [
                        "visitor" => $sessions[1]->visitor,
                        "client" => $sessions[1]->client,
                        "origin" => $sessions[0]->origin,
                        "created_at" => $sessions[1]->created_at,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_all_filtered_sessions()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $session = factory(Session::class)->create(["visitor" => "965", "client" => "OSX", "platform" => "Unity", "origin" => "Denmark"]);
        $missing = factory(Session::class)->create(["visitor" => "377", "client" => "Windows", "platform" => "Browser", "origin" => "Sweden"]);

        $response = $this->json("GET", "api/sessions?filter[visitor]={$session->visitor}");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "visitor" => $session->visitor,
                        "client" => $session->client,
                        "platform" => $session->platform,
                        "origin" => $session->origin,
                        "created_at" => $session->created_at,
                    ]
                ],
                "links" => [],
                "meta" => [],
            ])->assertJsonMissing($missing->toArray());
    }

    /** @test */
    public function a_user_can_view_all_sessions_with_relations()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $sessions = factory(Session::class, 2)->create();

        $response = $this->json("GET", "api/sessions?include=actions,variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "visitor" => $sessions[0]->visitor,
                        "client" => $sessions[0]->client,
                        "platform" => $sessions[0]->platform,
                        "origin" => $sessions[0]->origin,
                        "created_at" => $sessions[0]->created_at,
                        "actions" => [],
                        "variables" => [],
                    ],
                    [
                        "visitor" => $sessions[1]->visitor,
                        "client" => $sessions[1]->client,
                        "platform" => $sessions[1]->platform,
                        "origin" => $sessions[1]->origin,
                        "created_at" => $sessions[1]->created_at,
                        "actions" => [],
                        "variables" => [],
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_session()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $session = factory(Session::class)->create();

        $response = $this->json("GET", "api/sessions/$session->id");

        $response
            ->assertStatus(200)
            ->assertJson([
                "visitor" => $session->visitor,
                "client" => $session->client,
                "platform" => $session->platform,
                "origin" => $session->origin,
                "created_at" => $session->created_at,
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_session_with_its_relations()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $session = factory(Session::class)->create();

        $response = $this->json("GET", "api/sessions/{$session->id}?include=actions,variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "visitor" => $session->visitor,
                "client" => $session->client,
                "platform" => $session->platform,
                "origin" => $session->origin,
                "created_at" => $session->created_at,
                "actions" => [],
                "variables" => [],
            ]);
    }

    /** @test */
    public function anyone_can_create_a_new_session()
    {
        $this->withoutExceptionHandling();

        $data = [
            "visitor" => $this->faker->randomNumber(),
            "client" => $this->faker->randomElement(["Browser", "Unity"]),
            "platform" => $this->faker->randomElement(["OSX", "Windows", "Android", "iPhone"]),
            "origin" => $this->faker->country,
        ];

        $response = $this->json("POST", "api/sessions", $data);

        $this->assertDatabaseHas("sessions", $data);

        $response
            ->assertStatus(201)
            ->assertJson($data);
    }

    /** @test */
    public function a_user_can_delete_a_session()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $session = factory(Session::class)->create();

        $response = $this->json("DELETE", "api/sessions/$session->id");

        $this->assertDatabaseMissing("sessions", [
            "visitor" => $session->visitor,
            "client" => $session->client,
            "platform" => $session->platform,
            "origin" => $session->origin,
            "created_at" => $session->created_at,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "visitor" => $session->visitor,
                "client" => $session->client,
                "platform" => $session->platform,
                "origin" => $session->origin,
                "created_at" => $session->created_at,
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_sessions_actions()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $session = factory(Session::class)->create();
        $actions = factory(Action::class, 2)->create(["session_id" => $session->id]);

        $response = $this->json("GET", "api/sessions/$session->id/actions");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "location" => $actions[0]->location,
                        "target" => $actions[0]->target,
                        "created_at" => $actions[0]->created_at,
                    ],
                    [
                        "location" => $actions[1]->location,
                        "target" => $actions[1]->target,
                        "created_at" => $actions[1]->created_at,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_sessions_actions_variables()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $session = factory(Session::class)->create();
        $action = factory(Action::class)->create(["session_id" => $session->id]);
        $variables = factory(Variable::class, 2)->create(["action_id" => $action->id]);

        $response = $this->json("GET", "api/sessions/$session->id/variables");

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
    public function a_user_can_view_a_specific_sessions_filtered_actions()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $session = factory(Session::class)->create();

        $action = factory(Action::class)->create(["location" => "journey", "action" => "save",  "target" => "saved", "session_id" => $session->id]);
        $missing = factory(Action::class)->create(["location" => "start-learning", "action" => "view",  "target" => "viwed", "session_id" => $session->id]);

        $response = $this->json("GET", "api/sessions/$session->id/actions?filter[target]={$action->target}");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "location" => $action->location,
                        "target" => $action->target,
                        "created_at" => $action->created_at,
                    ]
                ],
                "links" => [],
                "meta" => [],
            ])->assertJsonMissing($missing->toArray());
    }

    /** @test */
    public function a_user_can_view_a_specific_sessions_actions_filtered_variables()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $session = factory(Session::class)->create();
        $action = factory(Action::class)->create(["session_id" => $session->id]);

        $variable = factory(Variable::class)->create(["variable" => "journey_id", "value" => 2, "action_id" => $action->id]);
        $missing = factory(Variable::class)->create(["variable" => "start-learning_id", "value" => 498, "action_id" => $action->id]);

        $response = $this->json("GET", "api/sessions/$session->id/variables?filter[value]={$variable->value}");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "variable" => $variable->variable,
                        "value" => $variable->value,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ])->assertJsonMissing($missing->toArray());
    }
}
