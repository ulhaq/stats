<?php

namespace Tests\Feature;

use App\Action;
use App\Session;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SessionTest extends TestCase
{
    use DatabaseMigrations, WithFaker;


    /** @test */
    public function a_user_can_view_all_sessions()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class, 2)->create();

        $response = $this->json("GET", "api/sessions");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "user" => $session[0]->user,
                        "client" => $session[0]->client,
                        "platform" => $session[0]->platform,
                        "created_at" => $session[0]->created_at,
                    ],
                    [
                        "user" => $session[1]->user,
                        "client" => $session[1]->client,
                        "platform" => $session[1]->platform,
                        "created_at" => $session[1]->created_at,
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

        $session = factory(Session::class)->create();

        $response = $this->json("GET", "api/sessions/$session->id");

        $response
            ->assertStatus(200)
            ->assertJson([
                "user" => $session->user,
                "client" => $session->client,
                "platform" => $session->platform,
                "created_at" => $session->created_at,
            ]);
    }

    /** @test */
    public function a_user_can_create_a_new_session()
    {
        $this->withoutExceptionHandling();

        $data = [
            "user" => $this->faker->randomNumber(),
            "client" => $this->faker->randomElement(["Browser", "Unity"]),
            "platform" => $this->faker->randomElement(["OSX", "Windows", "Android", "iPhone"]),
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

        $session = factory(Session::class)->create();

        $response = $this->json("DELETE", "api/sessions/$session->id");

        $this->assertDatabaseMissing("sessions", [
            "user" => $session->user,
            "client" => $session->client,
            "platform" => $session->platform,
            "created_at" => $session->created_at,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "user" => $session->user,
                "client" => $session->client,
                "platform" => $session->platform,
                "created_at" => $session->created_at,
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_sessions_actions()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();
        $action = factory(Action::class, 2)->create(["session_id" => $session->id]);

        $response = $this->json("GET", "api/sessions/$session->id/actions");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "location" => $action[0]->location,
                        "target" => $action[0]->target,
                        "created_at" => $action[0]->created_at,
                    ],
                    [
                        "location" => $action[1]->location,
                        "target" => $action[1]->target,
                        "created_at" => $action[1]->created_at,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }
}
