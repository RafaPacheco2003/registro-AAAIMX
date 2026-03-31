<?php

namespace App\Services;

use App\Models\Register;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RegisterService{

    public function __construct(
        protected MailService $mailService,
    ) {}

    public function getAll(){
        return Register::with(['category', 'subcategory', 'teamMembers'])->get();
    }

    public function create (array $data){
        if (array_key_exists('team_members', $data)) {
            return $this->persistRegisterWithTeamMembers($data);
        }

        return Register::create($data);
    }

    public function createAndDownloadPdf(array $data): Response
    {
        $register = $this->persistRegisterWithTeamMembers($data);

        $filename = "registro-{$register->registration_code}.pdf";

        $pdf = Pdf::loadView('pdf.register', ['register' => $register]);
        $binary = $pdf->output();

        foreach ($register->teamMembers->sortBy('id') as $member) {
            $this->mailService->sendRegistrationPdf(
                $member->personal_email,
                $binary,
                $filename
            );
        }

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function persistRegisterWithTeamMembers(array $data): Register
    {
        $teamMembers = $data['team_members'] ?? [];
        unset($data['team_members']);

        return DB::transaction(function () use ($data, $teamMembers) {
            $register = Register::create($data);

            foreach ($teamMembers as $member) {
                $register->teamMembers()->create($member);
            }

            return $register->load(['category', 'subcategory', 'teamMembers']);
        });
    }

    public function update (Register $register, array $data){
        unset($data['team_members']);
        $register->update($data);

        return $register->fresh(['category', 'subcategory', 'teamMembers']);
    }

    public function delete (Register $register){
        return $register->delete();
    }
}
