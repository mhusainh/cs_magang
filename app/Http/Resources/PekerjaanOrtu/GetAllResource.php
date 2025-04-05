<?php

namespace App\Http\Resources\PekerjaanOrtu;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GetAllResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($item) {
                return new getDetailResource($item);
            }),
            'meta' => [
                'total' => $this->collection->count()
            ]
        ];
    }
} 