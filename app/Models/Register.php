<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Register extends Model
{
    protected $table = 'register';

    public $timestamps = false;

    protected $fillable = [
        'team_name', 'robot_name', 'education_level', 'institution',
        'personal_email', 'institutional_email',
        'payment_status', 'payment_date',
        'confirmed_at', 'has_discount', 'comments', 'category_id', 'subcategory_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'has_discount' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Register $register) {
            do {
                $code = 'ROBO-'.strtoupper(Str::random(8));
            } while (Register::where('registration_code', $code)->exists());

            $register->registration_code = $code;

            if ($register->subcategory_id) {
                $sub = Subcategory::query()->find($register->subcategory_id);
                if ($sub !== null) {
                    $register->price = $sub->price;
                }
            }
        });

        static::updating(function (Register $register) {
            if ($register->isDirty('payment_status')) {
                self::syncPaymentDatesForStatus($register);
            }

            if ($register->isDirty('subcategory_id') && $register->subcategory_id) {
                $sub = Subcategory::query()->find($register->subcategory_id);
                if ($sub !== null) {
                    $register->price = $sub->price;
                }
            }
        });
    }

    /**
     * Monto a pagar: 50% del precio de lista solo cuando has_discount es true.
     */
    public function payablePrice(): ?float
    {
        if ($this->price === null) {
            return null;
        }

        $base = (float) $this->price;

        if ($this->has_discount === true) {
            return round($base * 0.5, 2);
        }

        return round($base, 2);
    }

    /**
     * payment_date = fecha límite para pagar (no se modifica al cambiar el estado).
     * confirmed_at = fecha en que el pago quedó confirmado.
     */
    protected static function syncPaymentDatesForStatus(Register $register): void
    {
        if ($register->payment_status === 'confirmed') {
            $register->confirmed_at = now()->toDateString();

            return;
        }

        $register->confirmed_at = null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);

    }

    public function subcategory()
    {

        return $this->belongsTo(Subcategory::class);

    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }
}
