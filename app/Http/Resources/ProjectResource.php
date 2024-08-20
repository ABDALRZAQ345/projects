<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       $data =parent::toArray($request);
       $data['tasks'] = new TaskCollection($this->tasks);
     $data['members']=new UserCollection($this->members);
     $data['comments']=new CommentController($this->comments);
       return $data;
    }
}
