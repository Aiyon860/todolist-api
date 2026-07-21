<?php

namespace App\Models;

use App\Enums\TodoPriority;
use App\Enums\TodoStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('todos')]
#[Fillable(['title', 'assignee', 'due_date', 'time_tracked', 'status', 'priority'])]
class Todo extends Model
{
    protected $attributes = [
        'time_tracked' => 0,
        'status' => TodoStatus::Pending->value
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'string',
            'assignee' => 'string',
            'due_date' => 'date',
            'time_tracked' => 'integer',
            'status' => TodoStatus::class,
            'priority' => TodoPriority::class
        ];
    }
}
