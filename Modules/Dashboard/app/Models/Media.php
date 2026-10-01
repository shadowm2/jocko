<?php

namespace Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['model_type', 'model_id', 'collection', 'disk', 'path', 'original_name', 'mime_type', 'size', 'order'])]
class Media extends Model
{
    public function publicPath(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function deleteFile(): void
    {
        $fileExists = Storage::disk($this->disk)->exists($this->path);
        if ($fileExists) {
            Storage::disk($this->disk)->delete($this->path);
        }
    }
}
