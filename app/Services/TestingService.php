<?php

namespace App\Services;

use App\Repositories\TestingRepository;

class TestingService
{
    protected $testingRepository;

    public function __construct(TestingRepository $testingRepository)
    {
        $this->testingRepository = $testingRepository;
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

        return $this->testingRepository->get($where, $search, $with, $orderBy, $perPage, $first);
    }

    public function show($id)
    {
        return $this->testingRepository->first($id);
    }

    public function create(array $data)
    {
        return $this->testingRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->testingRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->testingRepository->delete($id);
    }
}