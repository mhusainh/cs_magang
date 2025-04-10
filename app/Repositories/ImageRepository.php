<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;

class ImageRepository
{
    public function __construct(private Image $model) {}

    public function findById(int $id): ?Image
    {
        return $this->model->where('id', $id)->first();
    }


    public function create($uploadedImage, $cloudinary, array $imageable): Image
    {
        $data = [
            'public_id' => $cloudinary->getPublicId(),
            'file_name' => $uploadedImage->getClientOriginalName(),
            'file_type' => $uploadedImage->getClientMimeType(),
            'url' => $cloudinary->getSecurePath(),
            'secure_url' => $cloudinary->getSecurePath(),
            'size' => $cloudinary->getSize(),
            'imageable_type' => $imageable['imageable_type'],
            'imageable_id' => $imageable['imageable_id'],
        ];
        
        return $this->model->create($data);
    }

    public function update(Image $image, array $data): bool
    {
        return $image->update($data);
    }


    public function delete(Image $image): bool
    {
        return $image->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

} 