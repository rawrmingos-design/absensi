<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'total_hours',
        'status',
        'notes',
        'location'
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Accessors & Mutators
    public function getTotalWorkingHoursAttribute()
    {
        if ($this->clock_in && $this->clock_out) {
            $clockIn = Carbon::parse($this->clock_in);
            $clockOut = Carbon::parse($this->clock_out);
            
            $totalMinutes = $clockOut->diffInMinutes($clockIn);
            
            // Subtract break time if exists
            if ($this->break_start && $this->break_end) {
                $breakStart = Carbon::parse($this->break_start);
                $breakEnd = Carbon::parse($this->break_end);
                $breakMinutes = $breakEnd->diffInMinutes($breakStart);
                $totalMinutes -= $breakMinutes;
            }
            
            return round($totalMinutes / 60, 2);
        }
        
        return 0;
    }

    public function getIsLateAttribute()
    {
        if ($this->clock_in) {
            $clockIn = Carbon::parse($this->clock_in);
            $standardTime = Carbon::parse('08:00');
            return $clockIn->gt($standardTime);
        }
        
        return false;
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }
}
