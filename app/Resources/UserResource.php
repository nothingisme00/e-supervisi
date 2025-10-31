<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nik' => $this->nik,
            'nip' => $this->nip,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'mata_pelajaran' => $this->when($this->role === 'guru', $this->mata_pelajaran),
            'tingkatan' => $this->when($this->role === 'guru', $this->tingkatan),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}