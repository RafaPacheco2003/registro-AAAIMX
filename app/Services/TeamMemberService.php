<?php

namespace App\Services;

use App\Models\TeamMember;

class TeamMemberService{
    public function getAll(){
        return TeamMember::with('register')->get();
    }

    public function create(array $data){
        return TeamMember::create($data);
    }

    public function update(TeamMember $teamMember, array $data){
        $teamMember->update($data);
        return $teamMember;
    }

    public function delete(TeamMember $teamMember){
        return $teamMember->delete();
    }
}