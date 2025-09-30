<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'tasks'=>$this->whenLoaded('tasks',function (){
                return collect($this->tasks)->map(function ($value){
                    return new TaskResource($value);
                });
            }),
            'roles'=>$this->whenLoaded('roles',function (){
                return new RoleResource($this->roles);
            }),
            'permissions'=>$this->whenLoaded('permissions',function (){
                return collect($this->permissions)->map(function ($value){
                    return new PermissionsResource($value);
                });
            }),
            'created_at' => new DateResource($this->created_at),
            'updated_at' => new DateResource($this->updated_at),
        ];
    }
}
