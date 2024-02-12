<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    const LEVEL_ADMIN = 'ADMIN';

    const LEVEL_KASIR = 'KASIR';

    const LEVEL_MANAGER = 'MANAGER';

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'username',
        'password',
        'email',
        'phone',
        'photo',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function scopeSearch(Builder $query, ?string $search)
    {
        if ($search) {

            $query->where(function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }
    }

    public static function listRoles()
    {
        return [
            self::LEVEL_ADMIN => self::LEVEL_ADMIN,
            self::LEVEL_KASIR => self::LEVEL_KASIR,
            self::LEVEL_MANAGER => self::LEVEL_MANAGER,
        ];
    }

    public static function getRelativeAvatarPath()
    {
        return 'avatars/';
    }

    /**
     * Mendapatkan nama tabel
     *
     * @return string
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
