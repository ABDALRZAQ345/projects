<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;

class Task extends Model
{
    use HasFactory,HasApiTokens,Notifiable;
    protected $fillable=[
      'name',
'project_id'
    ];

    protected $casts=[
        'is_done'=> 'boolean',
    ];
protected $hidden=[
    'updated_at',

];
    protected $guarded = ['id','user_id'];

public function user(){
    return $this->belongsTo(User::class);
}
public function project()
{
    return $this->belongsTo(Project::class);
}

    protected static function booted(){

static::addGlobalScope('user_project',function (Builder $builder)  {
    $builder->where('user_id',Auth::id())->
    orWhereIn('project_id',Auth::user()->projects->pluck('id'));

});
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    /// fetch the task if


}
