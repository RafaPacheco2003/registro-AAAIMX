<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'team_name' => $this->team_name,
            'robot_name' => $this->robot_name,

            'education_level' => $this->education_level,
            'institution' => $this->institution,

            'registration_code' => $this->registration_code,
            'personal_email' => $this->personal_email,
            'institutional_email' => $this->institutional_email,
            'amount' => $this->amount,
            
            'payment_status' => $this->payment_status,
            'payment_date' => $this->payment_date,
            'confirmed_at' => $this->confirmed_at,
            'comments' => $this->comments,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'subcategory' => new SubcategoryResource($this->whenLoaded('subcategory')),
            'team_members' => TeamMemberResource::collection($this->whenLoaded('teamMembers')),
        ];
    }
}
