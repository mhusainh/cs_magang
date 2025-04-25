<?php

namespace App\Http\Resources\Jurusan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class GetUniqueJenjangSekolahResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public static function collection($resource)
    {
        $jenjangSekolah = collect($resource)
            ->pluck('jenjang_sekolah')
            ->unique()
            ->values()
            ->all();

        return [
            'jenjang_sekolah' => $jenjangSekolah
        ];
    }
}
