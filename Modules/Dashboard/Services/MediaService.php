<?php

namespace Modules\Dashboard\Services;

use App\Services\BaseService;
use Modules\Dashboard\Models\Media;
use Modules\Dashboard\Repositories\MediaRepository;

/**
 * @extends BaseService<Media, MediaRepository>
 */
class MediaService extends BaseService
{
    public function __construct(
        MediaRepository $repository
    ) {
        parent::__construct($repository);
    }
}
