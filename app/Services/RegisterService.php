<?php

namespace App\Services;

use App\Models\Register;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class RegisterService{

    public function getAll(){
        return Register::with('category','subcategory')->get();
    }

    public function create (array $data){
        return Register::create($data);
    }

    public function createAndDownloadPdf(array $data): Response
    {
        $register = Register::create($data);
        $register->load(['category', 'subcategory']);

        $filename = "registro-{$register->registration_code}.pdf";

        return Pdf::loadView('pdf.register', ['register' => $register])->download($filename);
    }

    public function update (Register $register, array $data){
        $register->update($data);
        return $register;
    }

    public function delete (Register $register){
        return $register->delete();
    }
}
