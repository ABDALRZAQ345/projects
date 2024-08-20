<?php

namespace Tests\Feature\controllers\TaskController;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use tests\TestCase;
class TestControllerDeleteTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authorized_can_delete_a_task(){
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $task= $user->tasks()->create(['name' =>'name']);
        $this->assertDatabaseHas('tasks', $task->toArray());
        $route=route('tasks.destroy',$task);
        $response=$this->deleteJson($route);
        $response->assertNoContent();
        $this->assertDatabaseMissing('tasks',$task->toArray());
    }
    public function test_no_access_can_not_delete_a_task(){
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $task= Task::factory()->create();
        $this->assertDatabaseHas('tasks', $task->toArray());
        $route=route('tasks.destroy',$task);
        $response=$this->deleteJson($route);
        $response->assertNotFound();
        $this->assertDatabaseHas('tasks', $task->toArray());
    }
    public function test_unauthorized_can_not_delete_a_task(){
        $user = User::factory()->create();

        $task= Task::factory()->create();
        $this->assertDatabaseHas('tasks', $task->toArray());
        $route=route('tasks.destroy',$task);
        $response=$this->deleteJson($route);
        $response->assertUnauthorized();
        $this->assertDatabaseHas('tasks',$task->toArray());
    }
    public function test_can_not_delete_task_as_a_project_member()
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
        $route=route('tasks.destroy',$task);

        $response=$this->deleteJson($route);
        $response->assertForbidden();
    }
}
