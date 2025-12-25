<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMedecin(): bool
    {
        return $this->role === 'medecin';
    }

    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function medecin()
    {
        return $this->hasOne(Medecin::class);
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
