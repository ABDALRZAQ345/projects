<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberCollection;
use App\Http\Resources\MemberResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Project;


use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MemberController extends Controller
{
    //

    public function index(Request $request,Project $project){
        Gate::authorize('showMembers',$project);
$members=$project->members;
return new MemberCollection($members);
    }
    public function store(Request $request,Project $project){
    Gate::authorize('editMembers',$project);
$request->validate([
    'user' => 'required|exists:users,id'
]);

$project->members()->syncWithoutDetaching([$request->user]);
return new UserCollection($project->members);
    }
    public function show(Request $request,Project $project,User $member)
    {
        Gate::authorize('showMembers',$project);



        if($member->projects->contains($project)){
            return new UserResource($member);
}
else return response()->json([],404);

    }
    public function update(Request $request,Project $project,User $user)
    {

    }
    public function destroy(Project $project,User $member){
        Gate::authorize('editMembers',$project);

abort_if($project->user_id ==$member->id,400,'Cant delete the user ');
$project->members()->detach([$member->id]);
return new UserCollection($project->members);
    }
}
