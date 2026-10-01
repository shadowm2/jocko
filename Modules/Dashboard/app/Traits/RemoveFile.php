<?php

namespace Modules\Dashboard\Traits;

trait RemoveFile
{
    public function removeFile(string $property, int $index, bool $multiple = true): void
    {
        $value = data_get($this, $property);
        if ($multiple) {
            unset($value[$index]);
            data_set(
                $this,
                $property,
                array_values($value)
            );
        } else {
            data_set($this, $property, []);
        }
    }
}
