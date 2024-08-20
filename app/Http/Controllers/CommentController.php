<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
class CommentController extends Controller
{
    //


    public function store(StoreCommentRequest $request,Task|Project $model){
$validated=$request->validated();
$comment=$model->comments()->make($validated);
$comment->user()->associate(Auth::user());
$comment->save();
return new CommentResource($comment);
    }
    public function index(Task|Project $model)
    {
        $comments= $model->comments()->paginate();
        return new CommentCollection($comments);
    }
    public function show(Request $request,Task|Project $model,Comment $comment)
    {
        $comm=$model->comments()->where('id',$comment->id)->firstOrFail();
    return new CommentResource($comm);
    }
    public function update()
    {

    }
    public function destroy(){

    }

}
