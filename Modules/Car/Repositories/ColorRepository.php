<?php

namespace Modules\Car\Repositories;

use App\Repositories\BaseRepository;
use Modules\Car\Models\Color;
use Modules\Car\Repositories\Contracts\ColorRepositoryInterface;

/**
 * @extends BaseRepository<Color>
 */
class ColorRepository extends BaseRepository implements ColorRepositoryInterface
{
    public function __construct(Color $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): ColorRepository
    {
        $this->query()
            ->with([]);

        return $this;
    }
}
