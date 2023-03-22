<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'birth_day',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_day' => 'datetime',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'emails',
        'phones',
    ];

    /**
     * Получить email адреса контакта.
     *
     * @return HasMany
     */
    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }

    /**
     * Получить телефонные номера контакта.
     *
     * @return HasMany
     */
    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class);
    }

    /**
     * Получить пользователя.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить поле дня рождения.
     *
     * @return Attribute
     */
    public function birthDay(): Attribute
    {
        return Attribute::get(function (string $date) {
            return Carbon::createFromTimeString($date)->format('Y-m-d');
        });
    }
}
