<?php

namespace App\Services;

use App\Repositories\MainmenuRepository;

class MainmenuService
{
    protected $mainmenuRepository;

    public function __construct(MainmenuRepository $mainmenuRepository)
    {
        $this->mainmenuRepository = $mainmenuRepository;
    }

    public function get($perPage = null)
    {
        $where = $search = [];
        $with = ['submenus'];
        $orderBy = [['sort', 'asc']];

        foreach (request()->all() as $key => $value) {
            if(!in_array($key,['where','with','search','orderBy','perPage','first'])){
                $$key = $value;
            }
        }

        if (request()->filled('keyword')) {
            $search = ['name' => request('keyword')];
        }

        return $this->mainmenuRepository->get($where, $search, $with, $orderBy, $perPage);
    }

    public function show($id)
    {
        return $this->mainmenuRepository->first($id);
    }

    public function create(array $data)
    {
        $lastId = $this->mainmenuRepository->lastId();
        $data['code'] = 'M'.($lastId+1).'S0';
        isset($data['power_action']) ? $data['power_action'] = implode(',',$data['power_action']) : '';
        return $this->mainmenuRepository->create($data);
    }

    public function update(array $data, $id)
    {
        isset($data['power_action']) ? $data['power_action'] = implode(',',$data['power_action']) : '';
        return $this->mainmenuRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->mainmenuRepository->delete($id);
    }

    public function sort($upDown, $id)
    {
        $article = $this->mainmenuRepository->first($id);

        if ($upDown == 'up') {
            $sort = ($article->sort) - 1.5;
        } else {
            $sort = ($article->sort) + 1.5;
        }

        $this->mainmenuRepository->update(['sort' => $sort], $id);
    }
}