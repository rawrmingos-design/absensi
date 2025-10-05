<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'head_of_department',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function activeEmployees()
    {
        return $this->hasMany(Employee::class)->where('status', 'active');
    }
}
