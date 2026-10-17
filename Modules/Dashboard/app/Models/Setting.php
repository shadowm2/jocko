<?php

namespace Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Key/value store for third-party credentials and other rarely changed
 * configuration. Every value is encrypted at rest.
 *
 * @property string $key
 * @property ?string $value
 */
#[Fillable(['key', 'value'])]
class Setting extends Model
{
    protected $casts = [
        'value' => 'encrypted',
    ];
}
