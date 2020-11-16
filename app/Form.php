<?php

namespace App;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::created(function (self $form) {
            $version = new FormVersion();

            $now = now();

            $version->name = $now->toDateTimeString();
            $version->published_at = $now;

            $form->versions()->save($version);
        });
    }

    public function getHashedIdAttribute()
    {
        return static::encodeId($this->getKey());
    }

    public function scopeWhereHashedId(Builder $query, $hashedId)
    {
        $query->where('id', static::decodeId($hashedId));
    }

    public static function encodeId($id)
    {
        return static::makeHashids()->encode($id);
    }

    public static function decodeId($hashedId)
    {
        $decoded = static::makeHashids()->decode($hashedId);

        return $decoded[0] ?? null;
    }

    protected static function makeHashids()
    {
        return new Hashids('ZzG9iOsXm8lC', 8);
    }

    public function isOwnedBy($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        return $this->user_id == $user;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function version()
    {
        return $this->hasOne(FormVersion::class, 'form_id')
                    ->orderByDesc('published_at');
    }

    public function versions()
    {
        return $this->hasMany(FormVersion::class, 'form_id');
    }

    public function fields()
    {
        return $this->hasMany(Field::class, 'form_id');
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class, 'form_id');
    }
}
