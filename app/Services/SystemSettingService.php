<?php

namespace App\Services;

use App\Repositories\SystemSettingRepository;

class SystemSettingService
{
    protected $systemSettingRepository;

    public function __construct(SystemSettingRepository $systemSettingRepository)
    {
        $this->systemSettingRepository = $systemSettingRepository;
    }

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

        return $this->systemSettingRepository->get($where, $search, $with, $orderBy, $perPage, $first);
    }

    public function show($id)
    {
        return $this->systemSettingRepository->first($id);
    }

    public function create(array $data)
    {
        return $this->systemSettingRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->systemSettingRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->systemSettingRepository->delete($id);
    }
}