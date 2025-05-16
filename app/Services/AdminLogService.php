<?php

namespace App\Services;

use App\Repositories\AdminLogRepository;
use Illuminate\Support\Facades\DB;
use App\Models\AdminLog as AdminLogDB;

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
    public function get($perPage = null, array $with = [], array $where = [], array $orderBy = [['id', 'desc']], array $search = [], bool $first = false)
    {
        foreach (request()->all() as $key => $value) {
            if(!in_array($key,['where','with','search','orderBy','perPage','first'])){
               ${$key} = $value;
            }
        }

        if (request()->filled('keyword')) {
            $search = ['name' => request('keyword')];
        }

        return $this->adminLogRepository->get($where, $search, $with, $orderBy, $perPage, $first);
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

    public function log(string $action, string|array|object $desc = '', ?int $adminId = null): \App\Models\AdminLog
    {
        // 1. Model to array
        if (is_object($desc) && method_exists($desc, 'toArray')) {
            $desc = $desc->toArray();
        }

        // 2. Array to JSON
        if (is_array($desc)) {
            try {
                $desc = json_encode($desc, JSON_UNESCAPED_UNICODE);
            } catch (\JsonException $e) {
                $desc = '[JSON 編碼失敗]';
            }
        }

        // 3. 寫入 log，admin_id 可外部指定（如登出前取得）
        $log = AdminLogDB::create([
            'admin_id'    => $adminId ?? auth('admin')->id(),
            'action'      => $action,
            'description' => $desc,
            'ip'          => $this->loginIp,
        ]);

        return $log;
    }

}