<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $table = 'register';
    public $timestamps = false;

    protected $fillable = [
        'team_name', 'robot_name', 'education_level', 'institution',
        'personal_email', 'institutional_email',
        'registration_code', 'amount', 'payment_status', 'payment_date',
        'confirmed_at', 'comments', 'category_id', 'subcategory_id',
    ];


    public function category(){
        return $this->belongsTo(Category::class);

    }

    public function subcategory(){

        return $this->belongsTo(Subcategory::class);

    }
}
