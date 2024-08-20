<?php

namespace Tests\Feature\controllers\TaskController;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use tests\TestCase;
class CommentControllerStoreTest extends TestCase
{

    public function test_authorized_can_store_comment_for_task()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $task = Task::factory()->for($user,'user')->create();
        $route=route('tasks.comments.store',$task);
        $response=$this->postJson($route,['body' =>'title']);
        $response->assertCreated();
        $this->assertDatabaseCount('comments',1);
        $this->assertDatabaseHas('comments',['body' =>'title']);
    }
    public function test_authorized_can_store_comment_for_project()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $project = Project::factory()->for($user,'user')->create();
        $route=route('projects.comments.store',$project);
        $response=$this->postJson($route,['body' =>'title']);
        $response->assertCreated();
        $this->assertDatabaseCount('comments',1);
        $this->assertDatabaseHas('comments',['body' =>'title']);
    }
    ///
    public function test_unauthorized_can_not_store_comment()
    {
        $task = Task::factory()->create();
        $route=route('tasks.comments.store',$task);
        $response=$this->postJson($route,['body' =>'title']);
        $response->assertUnauthorized();
        $this->assertDatabaseCount('comments',0);
        $this->assertDatabaseMissing('comments',['body' =>'title','user_id'=>$task->user->id,'commentable_type' =>Task::class]);
    }
    public function test_validation_error(){
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $task = Task::factory()->for($user,'user')->create();
        $route=route('tasks.comments.store',$task);
        $response=$this->postJson($route,[]);
        $response->assertJsonValidationErrors([
            'body'=>'required'
        ]);
        $this->assertDatabaseCount('comments',0);
        $this->assertDatabaseMissing('comments',[]);
    }
}
