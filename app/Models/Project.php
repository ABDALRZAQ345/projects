<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['title'];
    public function user()
    {
     return   $this->belongsTo(User::class);
    }
    public function members()
    {
        return   $this->belongsToMany(User::class,user_project::class);
    }
    public function tasks(){
        return $this->hasMany(Task::class);
    }
    protected static function booted(){
        static::addGlobalScope('user_project',function (Builder $builder){
            $builder->whereRelation('members','user_id',Auth::id());
        });
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
