<?php

namespace App\Repositories;

use App\Models\TotalDiscount;
use App\Traits\LoggableRepositoryTrait;

class TotalDiscountRepository
{
    use LoggableRepositoryTrait;

    protected $model;

    public function __construct(TotalDiscount $model)
    {
        $this->model = $model;
    }

    public function get(array $where = [], array $search = [], array $with = [], array $orderBy = [], int $perPage = null, bool $first = false)
    {
        $query = $this->model->newQuery();

        if (!empty($with)) {
            $query->with($with);
        }

        if (!empty($where)) {
            $query->where($where);
        }

        foreach ($search as $field => $keyword) {
            if ($keyword !== '') {
                $query->where($field, 'LIKE', '%' . addcslashes($keyword, '%_') . '%');
            }
        }

        foreach ($orderBy as $order) {
            if (!is_array($order) || count($order) !== 2) {
                continue;
            }
            [$column, $direction] = $order;
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($column, $direction);
        }

        if ($first === true) {
            return $query->first();
        }

        if (!empty($perPage)) {
            if (!empty($where)) {
                return $query->limit($perPage)->get();
            } else {
                return $query->paginate($perPage);
            }
        }

        return $query->get();
    }

    public function first($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);
        $this->logModelCreated('新增TotalDiscount', $model);
        return $model;
    }

    public function update(int $id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $original = $model->getOriginal();
        $model->update($data);
        $this->logModelChanges('修改TotalDiscount', $model, $original);
        return $model;
    }

    public function delete(int $id)
    {
        $model = $this->model->findOrFail($id);
        $this->logModelDeleted('刪除TotalDiscount', $model);
        return $model->delete();
    }
}