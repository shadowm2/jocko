<?php

namespace Modules\Dashboard\Repositories;

use App\Repositories\BaseRepository;
use Modules\Dashboard\Filters\BrandFilter;
use Modules\Dashboard\Models\Media;
use Modules\Dashboard\Repositories\Contracts\MediaRepositoryInterface;

/**
 * @extends BaseRepository<Media>
 */
class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    public function __construct(Media $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): MediaRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): MediaRepository
    {
        (new BrandFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
