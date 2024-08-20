<?php

namespace Tests\Feature\controllers\TaskController;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use tests\TestCase;
class TaskControllerindexTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authorized_users_can_fetch_task_list(): void
    {
        $route=route('tasks.index');
        $user=User::factory()->create();
        Sanctum::actingAs($user);
        Task::factory()->for($user,'user')->create();
        $response=$this->getJson($route);
        $response->assertOk()->assertJsonCount(1,'data');
    }

    public function test_unauthorized_users_can_not_fetch_task_list(): void
    {
        $route=route('tasks.index');
        $response=$this->getJson($route);
        $response->assertUnauthorized();
    }

/**
 *  @dataProvider filteredFields
 */
public function test_filtered_fields($filed,$value,$expected): void{
    $route=route('tasks.index',[
        "filter[{$filed}]"=> $value,
    ]);
    $user=User::factory()->create();
    Sanctum::actingAs($user);

    $response=$this->getJson($route);
    $response->assertStatus($expected);
}
public function filteredFields(): array{
        return [
          ['id',1,400],
          ['name','name',400],
            ['is_done',1,200],
        ];
}
///
    /**
     *  @dataProvider sortableFields
     */
    public function test_sortable_fields($filed,$expected): void{
        $route=route('tasks.index',[
            "sort"=> $filed
        ]);
        $user=User::factory()->create();
        Sanctum::actingAs($user);

        $response=$this->getJson($route);
        $response->assertStatus($expected);
    }
    public function sortableFields(): array{
     return   [
            ['id',400],
            ['name',400],
            ['is_done',200],
            ['created_at',200],
            ['updated_at',200],
        ];
    }
}
