<?php

namespace Obelaw\Pim\Repositories;

use Obelaw\Pim\Models\Category;

class CategoryRepository
{
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function all()
    {
        return Category::all();
    }
}
