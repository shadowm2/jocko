<?php

namespace Modules\Car\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Car\Models\Color;
use Modules\Car\Repositories\ColorRepository;

/**
 * @extends BaseService<Color, ColorRepository>
 */
class ColorService extends BaseService
{
    public function __construct(
        ColorRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Color>|Collection<int, Color>
     */
    public function getColors(array|bool $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        if (is_bool($filters)) {
            $paginate = $filters;
            $filters = [];
        }

        $this->repository->filtered($filters);
        if (! in_array($filters['sort_by'] ?? null, ['name', 'hex'], true)) {
            $this->repository->orderBy('hex')->orderBy('colors.id');
        }
        $query = $this->repository->withRelations();

        return $paginate ?
            $query->paginate() : $query->all();
    }

    public function create(array $data): Color
    {
        $data['slug'] = Utils::generateUniqueSlug($data['name'], Color::class);

        return $this->repository->create($data);
    }
}
