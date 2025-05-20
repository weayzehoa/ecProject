<?php

namespace App\Services;

use App\Repositories\TotalDiscountRepository;

class TotalDiscountService
{
    protected $totalDiscountRepository;

    public function __construct(TotalDiscountRepository $totalDiscountRepository)
    {
        $this->totalDiscountRepository = $totalDiscountRepository;
    }

    public function get($perPage = null, array $with = [], array $where = [], array $orderBy = [['amount', 'desc']], array $search = [], bool $first = false)
    {
        foreach (request()->all() as $key => $value) {
            if(!in_array($key,['where','with','search','orderBy','perPage','first'])){
               ${$key} = $value;
            }
        }

        if (request()->filled('keyword')) {
            $search = ['name' => request('keyword')];
        }

        return $this->totalDiscountRepository->get($where, $search, $with, $orderBy, $perPage, $first);
    }

    public function show($id)
    {
        return $this->totalDiscountRepository->first($id);
    }

    public function create(array $data)
    {
        return $this->totalDiscountRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->totalDiscountRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->totalDiscountRepository->delete($id);
    }
}