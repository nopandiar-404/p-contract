<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordChangeLog extends Model
{
    use HasFactory;

    protected $table = 'password_change_logs';

    protected $fillable = [
        'user_id',
        'email',
        'performed_by',
        'ip',
        'user_agent',
        'source',
    ];
}
