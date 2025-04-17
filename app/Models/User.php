<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'login',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Str::random(60);
        $this->password_reset_expires = now()->addHours(1);
        $this->save();
        
        return $this->password_reset_token;
    }

    public function clearPasswordResetToken()
    {
        $this->password_reset_token = null;
        $this->password_reset_expires = null;
        $this->save();
    }
}