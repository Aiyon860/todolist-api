<?php

namespace App\Models;

use App\Enums\TodoPriority;
use App\Enums\TodoStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
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

    #[Scope]
    protected function filtered(Builder $query, array $filters): void
    {
        $query
            ->when($filters['title'] ?? null, fn ($q, $v) => $q->where('title', 'like', "%{$v}%"))
            ->when($filters['assignee'] ?? null, fn ($q, $v) => $q->whereIn('assignee', explode(',', $v)))
            ->when($filters['start'] ?? null, fn ($q, $v) => $q->whereDate('due_date', '>=', $v))
            ->when($filters['end'] ?? null, fn ($q, $v) => $q->whereDate('due_date', '<=', $v))
            ->when($filters['min'] ?? null, fn ($q, $v) => $q->where('time_tracked', '>=', $v))
            ->when($filters['max'] ?? null, fn ($q, $v) => $q->where('time_tracked', '<=', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->whereIn('status', explode(',', $v)))
            ->when($filters['priority'] ?? null, fn ($q, $v) => $q->whereIn('priority', explode(',', $v)));
    }
}
