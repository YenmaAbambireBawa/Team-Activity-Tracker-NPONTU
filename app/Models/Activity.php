<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /*
     * Latest log for a given date — used on the daily view to show
     * the most recent status for each activity without loading all history.
     */
    public function latestLogForDate(string $date)
    {
        return $this->logs()
            ->where('log_date', $date)
            ->latest()
            ->first();
    }
}
