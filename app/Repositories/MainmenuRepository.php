<?php

namespace App\Repositories;

use App\Models\Mainmenu;
use App\Traits\LoggableRepositoryTrait;

class MainmenuRepository
{
    use LoggableRepositoryTrait;

    protected $model;

    public function __construct(Mainmenu $model)
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

    public function firstWhere($where)
    {
        return $this->model->where($where)->first();
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);
        $this->sort();
        $this->logModelCreated('新增主選單', $model);
        return $model;
    }

    /**
     * 更新資料並回傳模型實體
     *
     * @param array $data
     * @param int $id
     * @return \App\Models\Mainmenu
     */
    public function update(int $id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $original = $model->getOriginal();
        $model->update($data);
        $original['sort'] != $model->sort ?  $this->sort() : '';
        $this->logModelChanges('修改主選單', $model, $original);
        return $model;
    }

    public function delete(int $id)
    {
        $model = $this->model->findOrFail($id);
        $this->logModelDeleted('刪除主選單', $model);
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

    public function lastId()
    {
        return $this->model->latest('id')->value('id');
    }
}