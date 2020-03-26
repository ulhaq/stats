<?php

namespace Tests\Feature;

use App\Variable;
use App\Action;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VariableTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_view_all_variables()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $variables = factory(Variable::class, 2)->create();

        $response = $this->json("GET", "api/variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "variable" => $variables[0]->variable,
                        "value" => $variables[0]->value,
                        "created_at" => $variables[0]->created_at,
                    ],
                    [
                        "variable" => $variables[1]->variable,
                        "value" => $variables[1]->value,
                        "created_at" => $variables[1]->created_at,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_all_filtered_variables()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $variable = factory(Variable::class)->create(["variable" => "journey_id", "value" => 2]);
        $missing = factory(Variable::class)->create(["variable" => "start-learning_id", "value" => 498]);

        $response = $this->json("GET", "api/variables?filter[value]={$variable->value}");

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

    /** @test */
    public function a_user_can_view_all_variables_with_relation()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $variables = factory(Variable::class, 2)->create();

        $response = $this->json("GET", "api/variables?include=action");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "variable" => $variables[0]->variable,
                        "value" => $variables[0]->value,
                        "created_at" => $variables[0]->created_at,
                        "action" => [],
                    ],
                    [
                        "variable" => $variables[1]->variable,
                        "value" => $variables[1]->value,
                        "created_at" => $variables[1]->created_at,
                        "action" => [],
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_variable()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $variable = factory(Variable::class)->create();

        $response = $this->json("GET", "api/variables/$variable->id");

        $response
            ->assertStatus(200)
            ->assertJson([
                "variable" => $variable->variable,
                "value" => $variable->value,
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_variable_with_its_relation()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $variable = factory(variable::class)->create();

        $response = $this->json("GET", "api/variables/{$variable->id}?include=action");

        $response
            ->assertStatus(200)
            ->assertJson([
                "variable" => $variable->variable,
                "value" => $variable->value,
                "created_at" => $variable->created_at,
                "action" => [],
            ]);
    }

    /** @test */
    public function anyone_can_create_a_new_variable()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        $data = [
            "variable" => $this->faker->word,
            "value" => $this->faker->word,
            "action_id" => $action->id,
        ];

        $response = $this->json("POST", "api/variables", $data);

        $this->assertDatabaseHas("variables", $data);

        unset($data["action_id"]);

        $response
            ->assertStatus(201)
            ->assertJson($data);
    }

    /** @test */
    public function a_user_can_delete_a_variable()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $variable = factory(Variable::class)->create();

        $response = $this->json("DELETE", "api/variables/$variable->id");

        $this->assertDatabaseMissing("variables", [
            "variable" => $variable->variable,
            "value" => $variable->value,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "variable" => $variable->variable,
                "value" => $variable->value,
            ]);
    }
}
