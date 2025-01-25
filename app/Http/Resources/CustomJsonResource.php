<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomJsonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>|Arrayable<int|string, mixed>|\JsonSerializable
     */
    public function toArray(Request $request): array|Arrayable|\JsonSerializable
    {
        return parent::toArray($request);
    }
}
