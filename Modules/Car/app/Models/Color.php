<?php

namespace Modules\Car\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Car\Database\Factories\ColorFactory;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string $title
 * @property string $hex
 */
#[Fillable(['slug', 'title', 'name', 'hex'])]
class Color extends BaseModel
{
    use HasFactory;

    protected static function newFactory(): ColorFactory
    {
        return ColorFactory::new();
    }
}
