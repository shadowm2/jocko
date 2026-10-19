<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

// A view calling a route that was renamed or never existed only fails once that
// page has a row to render, so check every literal route name statically.
it('only references registered route names', function () {
    $files = collect(['app', 'resources', 'Modules'])
        ->flatMap(fn (string $dir) => File::allFiles(base_path($dir)))
        ->filter(fn ($file) => str_ends_with($file->getFilename(), '.php')
            && ! str_contains($file->getPathname(), '/tests/')
            && ! str_contains($file->getPathname(), '/node_modules/'));

    $missing = [];

    foreach ($files as $file) {
        $code = $file->getContents();
        preg_match_all('/route\(\s*[\'"]([\w.\-]+)[\'"]/', $code, $found, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        foreach ($found as $match) {
            [, $start] = $match[0];
            [$name] = $match[1];

            // request()->route('token') reads a URL parameter, not a route name.
            $before = substr($code, max(0, $start - 12), min(12, $start));
            if (preg_match('/(request\(\)|\$request)->$/', $before)) {
                continue;
            }

            if (! Route::has($name)) {
                $missing[] = "{$name} ({$file->getRelativePathname()})";
            }
        }
    }

    expect($missing)->toBe([]);
});
