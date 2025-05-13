<?php

namespace App\Repositories;

use App\Models\Store;
use App\Traits\LoggableRepositoryTrait;

class StoreRepository
{
    use LoggableRepositoryTrait;

    protected $model;

    public function __construct(Store $model)
    {
        $this->model = $model;
    }

    public function get(
        array $where = [],
        array $search = [],
        array $with = [],
        array $orderBy = [['id', 'desc']],
        int $perPage = null
    ) {
        $query = $this->model->newQuery();

        if (!empty($with)) {
            $query->with($with);
        }

        if (!empty($where)) {
            $query->where($where);
        }

        if (!empty($search['keyword'])) {
            $keyword = $search['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('tel', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%");
            });
        }

        foreach ($orderBy as $order) {
            if (!is_array($order) || count($order) !== 2) {
                continue;
            }
            [$column, $direction] = $order;
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($column, $direction);
        }

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function first($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);
        $this->logModelCreated('新增分店資訊', $model);
        return $model;
    }

    /**
     * 更新資料並回傳模型實體
     *
     * @param array $data
     * @param int $id
     * @return \App\Models\Store
     */
    public function update(int $id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $original = $model->getOriginal();
        $model->update($data);
        $original['sort'] != $model->sort ? $this->sort() : '';
        $this->logModelChanges('修改分店資訊', $model, $original);
        return $model;
    }

    public function delete(int $id)
    {
        $model = $this->model->findOrFail($id);
        $this->logModelDeleted('刪除分店資訊', $model);
        return $model->delete();
    }

    public function sort()
    {
        $models = $this->model->orderBy('sort','asc')->get();
        $i = 1;
        foreach ($models as $model) {
            $model->update(['sort' => $i]);
            $i++;
        }
    }
}