<?php

namespace Modules\Car\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Car\Models\Color;
use Modules\Car\Repositories\ColorRepository;

/**
 * @extends BaseService<Color>
 */
class ColorService extends BaseService
{
    public function __construct(
        ColorRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @return LengthAwarePaginator<int, Color>|Collection<int, Color>
     */
    public function getColors(bool $paginate = true): LengthAwarePaginator|Collection
    {
        $query = $this->repository
            ->withRelations()
            ->orderBy('hex');

        return $paginate ?
            $query->paginate() : $query->get();
    }

    public function create(array $data): Color
    {
        $data['slug'] = Utils::generateUniqueSlug($data['name'], Color::class);

        return $this->repository->create($data);
    }
}
