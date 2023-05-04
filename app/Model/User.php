<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Auth\IdentityInterface;


class User extends Model implements IdentityInterface
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'login',
        'role_id',
        'password',
    ];

    public $with = ['role'];

    protected static function booted()
    {
        static::created(function ($user) {
            $user->password = md5($user->password);
            $user->save();
        });
    }

    public function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function attemptIdentity(array $credentials)
    {
        return self::where([ 'login' => $credentials['login'],
            'password' => md5($credentials['password'])])->first();
    }
}