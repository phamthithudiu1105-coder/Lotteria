<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Notification extends Model
{
    protected $fillable = [
        'MaTaiKhoan',
        'type',
        'title',
        'message',
        'MaPhieuKiemKe',
        'data',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'MaTaiKhoan', 'MaTaiKhoan');
    }
}
