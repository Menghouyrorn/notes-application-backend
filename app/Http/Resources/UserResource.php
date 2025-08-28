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
            'created_at' => new DateResource($this->created_at),
            'updated_at' => new DateResource($this->updated_at),
        ];
    }
}
