<?php

namespace App\Services;

use App\Repositories\ImageSettingRepository;

class ImageSettingService
{
    protected $imageSettingRepository;

    public function __construct(ImageSettingRepository $imageSettingRepository)
    {
        $this->imageSettingRepository = $imageSettingRepository;
    }

    public function get($perPage = null)
    {
        $orderBy = $with = $where = $search = [];

        foreach (request()->all() as $key => $value) {
            ${$key} = $value;
        }

        if (request()->filled('keyword')) {
            $search = ['name' => request('keyword')];
        }

        return $this->imageSettingRepository->get($where, $search, $with, $orderBy, $perPage);
    }

    public function show($id)
    {
        return $this->imageSettingRepository->first($id);
    }

    public function create(array $data)
    {
        return $this->imageSettingRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->imageSettingRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->imageSettingRepository->delete($id);
    }
}