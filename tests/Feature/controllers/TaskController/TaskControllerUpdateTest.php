<?php

namespace Tests\Feature\controllers\TaskController;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Rules\is_same_user;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use tests\TestCase;
class TaskControllerUpdateTest extends TestCase
{
   public function test_authorized_can_update_task(){
       $user = User::factory()->create();
       Sanctum::actingAs($user);

       $task = Task::factory()->for($user,'user')->create();
       $route=route('tasks.update',$task);
       $response=$this->putJson($route,[
           'name'=>'updated task',
       ]);
       $response->assertOk();
       $task->refresh(); // refresh the task in database
       $this->assertEquals('updated task',$task->name);
   }
    public function test_unauthorized_can_update_task(){
        $task = Task::factory()->create();
        $route=route('tasks.update',$task);
        $response=$this->putJson($route,[
            'name'=>'updated task',
        ]);
        $response->assertUnauthorized();
    }
    public function test_can_not_update_task_as_a_project_member()
    {
    $user = User::factory()->create();
    $member=User::factory()->create();
    Sanctum::actingAs($user);
    $task = Task::factory()->for($user,'user')->create();
    $project = Project::factory()->for($user,'user')->create();
        $project->members()->syncWithoutDetaching([$member->id]);
   $task->update([
       'name' => $task->name,
      'project_id'=>$project->id,
   ]);
    Sanctum::actingAs($member);
        $route=route('tasks.update',$task);

        $response=$this->putJson($route,[
            'name'=>'updated task',
        ]);
        $response->assertForbidden();
    }
    public function test_no_access_to_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
Sanctum::actingAs($user);
        $route=route('tasks.update',$task);
        $response=$this->putJson($route,[
            'name'=>'updated task',
        ]);
        $response->assertNotFound();
    }

}
