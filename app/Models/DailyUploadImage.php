<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyUploadImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'client',
        'state',
        'branch',
        'path',
        'address',
    ];
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'employee_code');
    }

}
