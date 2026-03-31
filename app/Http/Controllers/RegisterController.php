<?php

namespace App\Http\Controllers;

use App\Services\RegisterService;
use App\Services\MailService;
use App\Models\Register;
use App\Http\Resources\RegisterResource;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Symfony\Component\Mailer\Exception\ExceptionInterface;

class RegisterController extends Controller
{
    protected $service;

    public function __construct(
        protected RegisterService $registerService,
        protected MailService $mailService,
    ) {}


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
    $this->mailService->sendTestEmail($request->to);

    return $this->success(null, 'Correo de prueba enviado exitosamente');
}
}
