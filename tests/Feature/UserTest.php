<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_view_all_users()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $users = factory(User::class, 2)->create();

        $response = $this->json("GET", "api/users");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id" => $users[0]->id,
                        "name" => $users[0]->name,
                        "email" => $users[0]->email,
                        "created_at" => $users[0]->created_at,
                        "updated_at" => $users[0]->updated_at,
                    ],
                    [
                        "id" => $users[1]->id,
                        "name" => $users[1]->name,
                        "email" => $users[1]->email,
                        "created_at" => $users[1]->created_at,
                        "updated_at" => $users[1]->updated_at,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_all_filtered_users()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $user = factory(User::class)->create(["name" => "Muhammad", "email" => "muhammad@example.com"]);
        $missing = factory(User::class)->create(["name" => "Umar", "email" => "umar@example.com"]);

        $response = $this->json("GET", "api/users?filter[name]={$user->name}");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                        "created_at" => $user->created_at,
                        "updated_at" => $user->updated_at,
                    ]
                ],
                "links" => [],
                "meta" => [],
            ])->assertJsonMissing($missing->toArray());
    }

    /** @test */
    public function a_user_can_view_a_specific_user()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $user = factory(User::class)->create();

        $response = $this->json("GET", "api/users/$user->id");

        $response
            ->assertStatus(200)
            ->assertJson([
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
            ]);
    }

    /** @test */
    public function a_user_can_create_a_new_user()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $data = [
            "name" => $this->faker->name,
            "email" => $this->faker->safeEmail,
            "password" => $this->faker->password,
        ];

        $response = $this->json("POST", "api/users", $data);

        $this->assertDatabaseHas("users", $data);

        unset($data["password"]);

        $response
            ->assertStatus(201)
            ->assertJson($data);
    }

    /** @test */
    public function a_user_can_delete_a_user()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->make());

        $user = factory(User::class)->create();

        $response = $this->json("DELETE", "api/users/$user->id");

        $this->assertDatabaseMissing("users", [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
            ]);
    }
}
