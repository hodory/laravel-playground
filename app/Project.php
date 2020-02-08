<?php
/**
 * Created by PhpStorm.
 * User: khzero
 * Date: 2019-12-24
 * Time: 오후 11:15
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public $old = [];
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'description' => $description,
            'changes' => [
                'before' => array_diff($this->old, $this->getAttributes()),
                'after' => array_diff($this->getAttributes(), $this->old),
            ],
        ]);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }
}
