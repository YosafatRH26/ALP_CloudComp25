<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin' | 'user'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed', // jika Laravel >= 9.24
    ];

    public function cvSubmissions()
    {
        return $this->hasMany(CvSubmission::class);
    }

    public function latestCvSubmission()
    {
        return $this->hasOne(CvSubmission::class)->latestOfMany();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
