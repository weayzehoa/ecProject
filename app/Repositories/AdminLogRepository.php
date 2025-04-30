<?php

namespace App\Repositories;

use App\Models\AdminLog;

class AdminLogRepository
{
    protected $model;

    public function __construct(AdminLog $model)
    {
        $this->model = $model;
    }

    /**
     * 取得資料（可條件搜尋、模糊搜尋、排序、分頁、關聯載入）
     *
     * @param  array       $where     精確條件，例如 ['is_active' => 1]
     * @param  array       $search    模糊搜尋，例如 ['name' => '股份']
     * @param  array       $with      預載入關聯，例如 ['company', 'users.roles']
     * @param  array       $orderBy   多欄排序，例如 [['created_at', 'desc'], ['id', 'asc']]
     * @param  int|null    $perPage   每頁筆數，若為 null 則不分頁
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function get(
        array $where = [],
        array $search = [],
        array $with = [],
        array $orderBy = [['id', 'desc']],
        ?int $perPage = null
    ) {
        $query = $this->model->newQuery();

        if (!empty($with)) {
            $query->with($with);
        }

        if (!empty($where)) {
            $query->where($where);
        }

        // 🔍 多欄 keyword 搜尋（含 admin 關聯）
        if (!empty($search['keyword'])) {
            $keyword = $search['keyword'];

            $query->where(function ($q) use ($keyword) {
                $q->where('action', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('ip', 'like', "%{$keyword}%")
                  ->orWhereHas('admin', function ($q2) use ($keyword) {
                      $q2->where('name', 'like', "%{$keyword}%")
                         ->orWhere('role', 'like', "%{$keyword}%")
                         ->orWhere('email', 'like', "%{$keyword}%")
                         ->orWhere('tel', 'like', "%{$keyword}%")
                         ->orWhere('mobile', 'like', "%{$keyword}%");
                  });
            });
        }

        foreach ($orderBy as $order) {
            if (is_array($order) && count($order) === 2) {
                [$column, $direction] = $order;
                $query->orderBy($column, strtolower($direction) === 'desc' ? 'desc' : 'asc');
            }
        }

        return $perPage ? $query->paginate($perPage) : $query->get();
    }


    public function first($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->model->findOrFail($id)->update($data);
    }

    public function delete(int $id)
    {
        return $this->model->where('id', $id)->delete();
    }
}