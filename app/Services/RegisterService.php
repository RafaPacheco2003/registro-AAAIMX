<?php

namespace App\Services;

use App\Models\Register;

class RegisterService{

    public function getAll(){
        return Register::with('category','subcategory')->get();
    }

    public function create (array $data){
        return Register::create($data);
    }

    public function update (Register $register, array $data){
        $register->update($data);
        return $register;
    }

    public function delete (Register $register){
        return $register->delete();
    }
}
