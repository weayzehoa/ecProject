<?php

namespace App\Services;

use App\Repositories\ShippingFeeRepository;

class ShippingFeeService
{
    protected $shippingFeeRepository;

    public function __construct(ShippingFeeRepository $shippingFeeRepository)
    {
        $this->shippingFeeRepository = $shippingFeeRepository;
    }

    public function get($perPage = null, array $with = [], array $where = [], array $orderBy = [['id', 'asc']], array $search = [], bool $first = false)
    {
        foreach (request()->all() as $key => $value) {
            if(!in_array($key,['where','with','search','orderBy','perPage','first'])){
               ${$key} = $value;
            }
        }

        if (request()->filled('keyword')) {
            $search = ['name' => request('keyword')];
        }

        return $this->shippingFeeRepository->get($where, $search, $with, $orderBy, $perPage, $first);
    }

    public function show($id)
    {
        return $this->shippingFeeRepository->first($id);
    }

    public function create(array $data)
    {
        return $this->shippingFeeRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->shippingFeeRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->shippingFeeRepository->delete($id);
    }
}