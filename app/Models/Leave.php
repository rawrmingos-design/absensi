<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'admin_notes',
        'approved_at',
        'approved_by',
        'attachment'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors & Mutators
    public function getCalculatedTotalDaysAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;
        }
        
        return 0;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];
        
        return $badges[$this->status] ?? 'secondary';
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'annual' => 'Cuti Tahunan',
            'sick' => 'Sakit',
            'maternity' => 'Cuti Melahirkan',
            'paternity' => 'Cuti Ayah',
            'emergency' => 'Darurat',
            'unpaid' => 'Tanpa Gaji'
        ];
        
        return $labels[$this->type] ?? $this->type;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('start_date', Carbon::now()->year);
    }
}
