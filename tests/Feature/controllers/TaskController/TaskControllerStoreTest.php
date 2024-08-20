<?php

namespace Tests\Feature\controllers\TaskController;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Rules\is_same_user;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use tests\TestCase;
class TaskControllerStoreTest extends TestCase
{
public function test_can_create_task()
{
    $user=User::factory()->create();
    Sanctum::actingAs($user);


    $route=route('tasks.store');
    $response=$this->postJson($route,['name' => 'name']);
    $response->assertCreated();
 $this->assertDatabaseHas('tasks',['name'=>'name']);
}
    public function test_unauthorized_can_create_task()
    {

        $route=route('tasks.store');
        $response=$this->postJson($route,['name' => 'name']);
        $response->assertUnauthorized();
        $this->assertDatabaseMissing('tasks',['name'=>'name']);
    }
    public function test_title_is_required(){
    $user=User::factory()->create();
    Sanctum::actingAs($user);
    $route=route('tasks.store');
    $response=$this->postJson($route);
    $response->assertJsonValidationErrors([
        'name' => 'required'
    ]);
    }
    public function test_valid_project_id(){
        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $route=route('tasks.store');
       $project= Project::factory()->for($user,'user')->create();
        $response=$this->postJson($route,[
            'name' => 'name',
            'project_id' => $project->id
        ]);
        $response->assertCreated();
    }
    public function test_not_valid_project_id(){
        $user=User::factory()->create();
        Sanctum::actingAs($user);
        $route=route('tasks.store');
        $project= Project::factory()->create();
        $response=$this->postJson($route,[
            'name' => 'name',
            'project_id' => $project->id
        ]);
        $response->assertJsonValidationErrors([
            'project_id' => [
                "you are not authorized to perform this action"
            ]
        ]);
        $response=$this->postJson($route,[
            'name' => 'name',
            'project_id' => Project::count()*2000
        ]);
        $response->assertJsonValidationErrors([
            'project_id' => [
                "The selected project id is invalid."
            ]
        ]);
    }

}
