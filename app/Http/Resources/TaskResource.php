<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data= parent::toArray($request);
        $data['status']=$this->is_done ?'finished ' :'open';
        $data['Creator']=User::find($this->user_id)->name;
        return $data;
    }
}
