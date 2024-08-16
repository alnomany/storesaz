<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'date',
        'description',
        'amount',
        'attachment',
        'project_id',
        'task_id',
        'created_by',
    ];
}
