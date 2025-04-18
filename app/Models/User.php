<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['admin', 'guru', 'siswa', 'super_admin']);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function bookLoans()
    {
        return $this->hasMany(BookLoan::class);
    }

    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class);
    }
    // Di model User
    protected static function booted()
    {
        static::created(function (User $user) {
            if ($user->type === 'student') {
                $user->student()->create(['full_name' => $user->name]);
            } elseif ($user->type === 'teacher') {
                $user->teacher()->create(['full_name' => $user->name]);
            }
        });

        static::updating(function (User $user) {
            // Update full_name jika name diubah
            if ($user->isDirty('name')) {
                if ($user->type === 'student' && $user->student) {
                    $user->student->update(['full_name' => $user->name]);
                } elseif ($user->type === 'teacher' && $user->teacher) {
                    $user->teacher->update(['full_name' => $user->name]);
                }
            }
        });
    }
}
