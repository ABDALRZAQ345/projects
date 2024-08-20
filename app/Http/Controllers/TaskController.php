<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    //
    public function index(Request $request){
Gate::authorize('viewAny',Task::class);
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters('is_done')
            ->defaultSort('is_done','name')->
            allowedSorts('is_done','created_at','updated_at')
            ->paginate();
        return new TaskCollection($tasks);

    }
    public function show(Request $request,Task $task){
   Gate::authorize('view',$task);

        return new TaskResource($task);
    }
    public function store(StoreRequest $request){

        $val=$request->validated();
$task=Auth::user()->tasks()->create($val);

        return new TaskResource($task);

    }
    public function update(StoreRequest $request,Task $task){
        Gate::authorize('update',$task);

        $val=$request->validated();
        $task->update($val);
    return new TaskResource($task);
    }
    public function destroy(Request $request,Task $task)
    {
        Gate::authorize('delete',$task);

        $task->delete();

        return response()->noContent();
    }
}
