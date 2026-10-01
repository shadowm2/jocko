<?php

namespace Modules\Dashboard\Livewire\Forms;

use _PHPStan_02959ca10\Nette\Neon\Exception;
use Illuminate\Support\Collection;
use Livewire\Form;
use Modules\Dashboard\Models\Media;

class ImageForm extends Form
{
    public ?array $images = [];

    public ?array $prev_images = [];

    public array $deleted_images = [];

    public function getImageRules(Collection|Media|null $media = null): array
    {
        $rules = [
            'deleted_images' => 'array',
            'deleted_images.*' => ' integer|exists:Modules\Dashboard\Models\Media,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,svg,webp|max:2048',
        ];
        if ($media === null) {
            $media = collect();
        } elseif ($media instanceof Media) {
            $media = collect([$media]);
        }

        $deletedImages = $media->pluck('id')->diff(collect($this->prev_images)->pluck('id'));
        foreach ($deletedImages as $key => $id) {
            $this->deleted_images[] = $id;
        }

        return $rules;
    }

    /**
     * @throws Exception
     */
    public function setPreviousImages(Collection|Media|null $images): void
    {
        if ($images instanceof Collection && $images->isNotEmpty() && $images->first() instanceof Media === false) {
            throw new Exception('previous images should be an instance of Media');
        }
        if ($images instanceof Media) {
            $images = collect([$images]);
        } elseif (is_null($images)) {
            $images = collect();
        }
        $this->prev_images = $images->all();
    }
}
