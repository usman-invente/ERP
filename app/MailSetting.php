<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'mailer', 'host', 'port', 'username', 'password', 'encryption', 'from_address', 'from_name'
    ];
}
