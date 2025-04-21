<?php

namespace App\Repositories;

use App\Models\Image;
use App\Models\MediaBerita;
use App\Models\MediaHomepage;
use Illuminate\Database\Eloquent\Collection;

class ImageRepository
{
    public function __construct(
        private MediaBerita $berita,
        private MediaHomepage $homepage
    ) {}

    public function createHomepage($cloudinary, int $urutan): MediaHomepage
    {
        $data = [
            'url' => $cloudinary->getSecurePath(),
            'public_id' => $cloudinary->getPublicId(),
            'urutan' => $urutan,
        ];
        return $this->homepage->create($data);
    }

    public function createBerita($cloudinary, array $data): MediaBerita
    {
        $data = [
            'url' => $cloudinary->getSecurePath(),
            'public_id' => $cloudinary->getPublicId(),
            'jenjang_sekolah' => $data['jenjang_sekolah'],
            'urutan' => $data['urutan'],
        ];
        return $this->berita->create($data);
    }

    public function getHomepage(): Collection
    {
        return $this->homepage->orderBy('urutan', 'asc')->get();
    }

    public function getBerita(): Collection
    {
        return $this->berita->orderBy('urutan', 'asc')->get();
    }

    public function getBeritaByUser(string $jenjang_sekolah): Collection
    {
        return $this->berita->where('jenjang_sekolah', $jenjang_sekolah)->orderBy('urutan', 'asc')->get();
    }

    public function updateHomepage(?object $cloudinary, $urutan, int $id): bool
    {
        $data = ['urutan' => $urutan];
        if ($cloudinary) {
            $data['url'] = $cloudinary->getSecurePath();
            $data['public_id'] = $cloudinary->getPublicId();
        }
        return $this->homepage->where('id', $id)->update($data);
    }

    public function updateBerita(?object $cloudinary, $urutan, int $id): bool
    {
        $data = ['urutan' => $urutan];
        if ($cloudinary) {
            $data['url'] = $cloudinary->getSecurePath();
            $data['public_id'] = $cloudinary->getPublicId();
        }
        return $this->berita->where('id', $id)->update($data);
    }

    public function findHomepageByID(int $id): ?MediaHomepage
    {
        return $this->homepage->where('id', $id)->first();
    }

    public function findBeritaByID(int $id): ?MediaBerita
    {
        return $this->berita->where('id', $id)->first();
    }

    public function deleteHomepage(int $id): bool
    {
        return $this->homepage->where('id', $id)->delete();
    }

    public function deleteBerita(int $id): bool
    {
        return $this->berita->where('id', $id)->delete();
    }
}
