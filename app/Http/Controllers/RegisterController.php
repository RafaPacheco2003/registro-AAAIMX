<?php

namespace App\Http\Controllers;

use App\Services\RegisterService;
use App\Models\Register;
use App\Http\Resources\RegisterResource;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $service;

   public function __construct(RegisterService $service){
    $this->service = $service;
    
   }


   public function index(){
    $registers = RegisterResource::collection($this->service->getAll());
    return $this->success($registers, 'Registros obtenidos exitosamente');
   }

   public function store(RegisterRequest $request){
    return $this->service->createAndDownloadPdf($request->validated());
}

   public function show(Register $register){
    $register->load(['category', 'subcategory', 'teamMembers']);
    return $this->success(new RegisterResource($register), 'Registro obtenido exitosamente');
   }

   public function update(RegisterRequest $request, Register $register){
    $register = $this->service->update($register, $request->validated());
    return $this->success(new RegisterResource($register), 'Registro actualizado exitosamente');
   }

   public function destroy(Register $register){
    $this->service->delete($register);
    return $this->success(null, 'Registro eliminado exitosamente');
   }



   public function sendTestEmail(Request $request)
{
    $request->validate([
        'to' => 'required|email',
    ]);
    Mail::raw('Este es un correo de prueba desde la API de RoboRage.', function ($message) use ($request) {
        $message->to($request->to)
                ->subject('Correo de prueba - RoboRage');
    });
    return $this->success(null, 'Correo de prueba enviado exitosamente');
}




    //
}
