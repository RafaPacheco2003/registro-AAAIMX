<?php

namespace App\Http\Controllers;

use App\Services\RegisterService;
use App\Models\Register;
use App\Http\Resources\RegisterResource;
use App\Http\Requests\RegisterRequest;

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
    $register = $this->service->create($request->validated());
    return $this->success(new RegisterResource($register), 'Registro creado exitosamente', 201);
   }

   public function show(Register $register){
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



   




    //
}
