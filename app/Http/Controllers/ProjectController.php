<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProject;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProject;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    //
//    public function __construct()
//    {
//$this->authorizeResource(Project::class,'project');
//    }
    public function index(){
        Gate::authorize('viewAny',Project::class);
$projects= Project::all();

return  new ProjectCollection($projects);
    }
    public function create(){

    }
    public function store(StoreProjectRequest $request){
        $validated=$request->validated();
$project=Auth::user()->project()->create($validated);


return new ProjectResource($project);
    }
    public function show(Request $request,Project $project){
        Gate::authorize('view', $project);
return new ProjectResource($project);
    }
    public function edit($id){

    }
    public function update(UpdateProject $request,Project $project){
        Gate::authorize('update', $project);
$validated=$request->validated();
$project->update($validated);
return new ProjectResource($project);
    }
    public function destroy(Request $request,Project $project)
    {
        Gate::authorize('delete', $project);
     $project->delete();
     return redirect()->route('projects.index');
    }
}
