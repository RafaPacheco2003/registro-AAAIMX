<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterIndexRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RegisterResource;
use App\Models\Register;
use App\Services\MailService;
use App\Services\RegisterService;

class RegisterController extends Controller
{
    public function __construct(
        protected RegisterService $registerService,
        protected MailService $mailService,
    ) {}

    public function index(RegisterIndexRequest $request)
    {
        $filters = array_intersect_key(
            $request->validated(),
            array_flip(['payment_status', 'registration_code', 'team_name', 'robot_name'])
        );

        $registers = RegisterResource::collection(
            $this->registerService->getAll($filters)
        );

        return $this->success($registers, 'Registros obtenidos exitosamente');
    }

    public function store(RegisterRequest $request)
    {
        return $this->registerService->createAndDownloadPdf($request->validated());
    }

    public function show(Register $register)
    {
        $register->load(['category', 'subcategory', 'teamMembers']);

        return $this->success(new RegisterResource($register), 'Registro obtenido exitosamente');
    }

    public function update(RegisterRequest $request, Register $register)
    {
        $register = $this->registerService->update($register, $request->validated());

        return $this->success(new RegisterResource($register), 'Registro actualizado exitosamente');
    }

    public function destroy(Register $register)
    {
        $this->registerService->delete($register);

        return $this->success(null, 'Registro eliminado exitosamente');
    }
}
