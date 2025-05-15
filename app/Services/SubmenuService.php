<?php

namespace App\Services;

use App\Repositories\SubmenuRepository;

class SubmenuService
{
    protected $submenuRepository;

    public function __construct(SubmenuRepository $submenuRepository)
    {
        $this->submenuRepository = $submenuRepository;
    }

    public function get($perPage = null, array $with = [], array $where = [], array $orderBy = [['id', 'desc']], array $search = [])
    {
        foreach (request()->all() as $key => $value) {
            ${$key} = $value;
        }

        if (request()->filled('keyword')) {
            $search = ['name' => request('keyword')];
        }

        return $this->submenuRepository->get($where, $search, $with, $orderBy, $perPage);
    }

    public function show($id)
    {
        return $this->submenuRepository->first($id);
    }

    public function create(array $data)
    {
        return $this->submenuRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->submenuRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->submenuRepository->delete($id);
    }
}