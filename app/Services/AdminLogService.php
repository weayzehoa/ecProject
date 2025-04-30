<?php

namespace App\Services;

use App\Repositories\AdminLogRepository;
use Illuminate\Support\Facades\DB;

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
        // 1. 如果傳來的是 Model，就轉陣列
        if (is_object($desc) && method_exists($desc, 'toArray')) {
            $desc = $desc->toArray();
        }

        // 2. 陣列一次性 encode 為 JSON（保留中文）
        if (is_array($desc)) {
            try {
                $desc = json_encode($desc, JSON_UNESCAPED_UNICODE);
            } catch (\JsonException $e) {
                $desc = '[JSON 編碼失敗]';
            }
        }

        // 3. 用 Model::create() 寫入
        $log = AdminLog::create([
            'admin_id'    => auth('admin')->id(),
            'action'      => $action,
            'description' => $desc,       // 已是 JSON 字串或原生字串
            'ip'          => $this->loginIp,
        ]);

        return $log;
    }

}