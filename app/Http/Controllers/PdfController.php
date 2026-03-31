<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function registers()
    {
        $registers = Register::with(['category', 'subcategory'])->get();

        $pdf = Pdf::loadView('pdf.registers', ['registers' => $registers])
            ->setPaper('a4', 'landscape');

        return $pdf->download('registros-roborage.pdf');
    }

    public function register(Register $register)
    {
        $register->load(['category', 'subcategory', 'teamMembers']);

        $pdf = Pdf::loadView('pdf.register', ['register' => $register]);

        return $pdf->download("registro-{$register->registration_code}.pdf");
    }
}
