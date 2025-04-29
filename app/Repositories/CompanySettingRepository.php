<?php

namespace App\Repositories;

use App\Models\CompanySetting;

class CompanySettingRepository
{
    protected $model;

    public function __construct(CompanySetting $model)
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
        int $perPage = null
    ) {
        $query = $this->model->newQuery();

        // 預載入關聯
        if (!empty($with)) {
            $query->with($with);
        }

        // 條件搜尋
        if (!empty($where)) {
            $query->where($where);
        }

        // 模糊搜尋（排除空值）
        foreach ($search as $field => $keyword) {
            if ($keyword !== '') {
                $query->where($field, 'LIKE', '%' . addcslashes($keyword, '%_') . '%');
            }
        }

        // 多組排序
        foreach ($orderBy as $order) {
            if (!is_array($order) || count($order) !== 2) {
                continue; // 跳過格式不正確的
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
