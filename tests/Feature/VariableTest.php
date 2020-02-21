<?php

namespace Tests\Feature;

use App\Variable;
use App\Action;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VariableTest extends TestCase
{
    use DatabaseMigrations, WithFaker;


    /** @test */
    public function a_user_can_view_all_variables()
    {
        $this->withoutExceptionHandling();

        $variable = factory(Variable::class, 2)->create();

        $response = $this->json("GET", "api/variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "variable" => $variable[0]->variable,
                        "value" => $variable[0]->value,
                    ],
                    [
                        "variable" => $variable[1]->variable,
                        "value" => $variable[1]->value,
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
    public function a_user_can_create_a_new_variable()
    {
        $this->withoutExceptionHandling();

        $action = factory(Action::class)->create();

        $data = [
            'variable' => $this->faker->word,
            'value' => $this->faker->word,
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
