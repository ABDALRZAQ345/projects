<?php

namespace Tests\Feature\controllers\TaskController;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use tests\TestCase;
class TaskControllerShowTest extends TestCase
{
    public function test_authorized_can_show_test()
    {
        $user=User::factory()->create();
        $task=Task::factory()->for($user,'user')->create();
        Sanctum::actingAs($user);
        $route=route('tasks.show',$task);
        $response=$this->getJson($route);
//$response->dd();
        $response->assertOk()
            ->assertJson([
                'data' =>[
             'id'=>$task->id,
             'name'=>$task->name,
             'is_done'=>$task->is_done,
             'created_at'=>$task->created_at->jsonSerialize(),
         ]
        ]);
    }
    public function test_unauthorized_can_show_test()
    {

        $task=Task::factory()->create();
        $route=route('tasks.show',$task);
        $response=$this->getJson($route);
        $response->assertUnauthorized();
    }
    public function test_no_access_user_can_not_show_test()
    {

        $user=User::factory()->create();
        $task=Task::factory()->create();
        Sanctum::actingAs($user);
        $route=route('tasks.show',$task);
        $response=$this->getJson($route);
        $response->assertStatus(404);
    }


}
