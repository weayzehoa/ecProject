<?php

namespace App\Services;

use App\Repositories\AdminLogRepository;

class AdminLogService
{
    protected $adminLogRepository;

    public function __construct(AdminLogRepository $adminLogRepository)
    {
        $this->adminLogRepository = $adminLogRepository;
        //改走cloudflare需抓x-forwareded-for
        if(!empty(request()->header('x-forwarded-for'))){
            $this->loginIp = request()->header('x-forwarded-for');
        }else{
            $this->loginIp = request()->ip();
        }
    }

    /**
     * 取得資料（條件搜尋、模糊搜尋、關聯、排序、分頁）
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function get($perPage)
    {
        $with = $where = $search = [];
        $orderBy = [['id', 'desc']];

        foreach (request()->all() as $key => $value) {
            $$key = $value;
        }

        // 自訂搜尋條件
        if (request()->filled('keyword')) {
            $search = [
                'name' => request('keyword')
            ];
        }

        return $this->adminLogRepository->get($where, $search, $with, $orderBy, $perPage);
    }

    public function show($id)
    {
        return $this->adminLogRepository->first($id);
    }

    public function update(array $data, $id)
    {
        return $this->adminLogRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->adminLogRepository->delete($id);
    }

    public function log(string $action, string|array|object $desc = ''): \App\Models\AdminLog
    {
        // 統一處理 description 成 json 字串
        if (is_object($desc)) {
            // 優先使用 toArray (Eloquent 支援)，否則轉成 JSON 字串
            if (method_exists($desc, 'toArray')) {
                $desc = $desc->toArray();
            } else {
                try {
                    $desc = json_encode($desc, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
                } catch (\JsonException $e) {
                    $desc = '[轉換失敗: ' . $e->getMessage() . ']';
                }
            }
        }

        if (is_array($desc)) {
            try {
                $desc = json_encode($desc, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $desc = '[轉換失敗: ' . $e->getMessage() . ']';
            }
        }

        return $this->adminLogRepository->create([
            'admin_id'   => auth('admin')->id() ?? null,
            'ip'         => $this->loginIp,
            'action'     => $action,
            'description'=> $desc,
        ]);
    }
}