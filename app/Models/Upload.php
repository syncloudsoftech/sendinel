<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Upload extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'disk', 'name', 'size', 'path', 'sender', 'comments', 'recipient', 'password', 'expires_at', 'hash', 'ua_ip',
        'ua_browser', 'ua_os', 'ua_device',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    public function getUrlAttribute(): string
    {
        return route('download.index', $this->hash);
    }
}
