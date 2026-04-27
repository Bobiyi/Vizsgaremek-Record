<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    public $table= "user";

    public $incrementing = true;

    protected $primaryKey = 'id';
    
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,Notifiable, HasFactory;



    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'phone',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function favourites(): BelongsToMany {
        return $this->belongsToMany(Record::class,'favourite','user_id','record_id');
    }

    public function requests() {
        return $this->hasMany(RequestQueue::class,'user_id','id');
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }
}
