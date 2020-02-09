<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    use RecordsActivity;

    protected static $recordableEvents = ["created", "deleted"];
    protected $guarded = [];
    protected $touches = [
        'project',
    ];
    protected $casts = [
        'completed' => 'boolean',
    ];

    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('uncompleted_task');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }
}
