<?php

namespace Tests\Feature\StatsTest;

use App\Action;
use App\Session;
use App\Variable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DetailTest extends TestCase
{
    use DatabaseMigrations, WithFaker;


    /** @test */
    public function a_user_can_view_a_specific_sessions_details()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();
        $action = factory(Action::class)->create(["session_id" => $session->id]);
        $variable = factory(Variable::class, 2)->create(["action_id" => $action->id]);

        $response = $this->json("GET", "api/stats/details/$session->id");

        $response
            ->assertStatus(200)
            ->assertJson([
                'user' => $session->user,
                'client' => $session->client,
                'platform' => $session->platform,
                'created_at' => $session->created_at,
                'actions' => [
                    [
                        'location' => $action->location,
                        'action' => $action->action,
                        'target' => $action->target,
                        'created_at' => $action->created_at,
                        'variables' => [
                            [
                                'variable' => $variable[0]->variable,
                                'value' => $variable[0]->value,
                            ],
                            [
                                'variable' => $variable[1]->variable,
                                'value' => $variable[1]->value,
                            ],
                        ],
                    ],
                ],
            ]);
    }
}
