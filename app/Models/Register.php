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
        'amount', 'payment_status', 'payment_date',
        'confirmed_at', 'comments', 'category_id', 'subcategory_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Register $register) {
            do {
                $code = 'ROBO-' . strtoupper(Str::random(8));
            } while (Register::where('registration_code', $code)->exists());

            $register->registration_code = $code;
        });
    }


    public function category(){
        return $this->belongsTo(Category::class);

    }

    public function subcategory(){

        return $this->belongsTo(Subcategory::class);

    }
}
