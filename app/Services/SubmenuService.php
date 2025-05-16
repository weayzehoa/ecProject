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

    public function get($perPage = null, array $with = [], array $where = [], array $orderBy = [['sort', 'asc']], array $search = [])
    {
        foreach (request()->all() as $key => $value) {
            if(!in_array($key,['where','with','search','orderBy','perPage','first'])){
                $$key = $value;
            }
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
        isset($data['power_action']) ? $data['power_action'] = implode(',',$data['power_action']) : '';
        $submenu = $this->submenuRepository->get($where = [['mainmenu_id',$data['mainmenu_id']]], $search = [], $with = [], $orderBy = [['code','desc']], $perPage = null, $first = true);
        $lastCode = explode('S',$submenu->code);
        $sNumber = isset($lastCode[1]) ? (int)$lastCode[1] : 0;
        $data['code'] = 'M'.$data['mainmenu_id'].'S'.($sNumber+1);
        return $this->submenuRepository->create($data);
    }

    public function update(array $data, $id)
    {
        isset($data['power_action']) ? $data['power_action'] = implode(',',$data['power_action']) : '';
        return $this->submenuRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->submenuRepository->delete($id);
    }

    public function sort($upDown, $id)
    {
        $article = $this->submenuRepository->first($id);

        if ($upDown == 'up') {
            $sort = ($article->sort) - 1.5;
        } else {
            $sort = ($article->sort) + 1.5;
        }

        $this->submenuRepository->update($id, ['sort' => $sort]);
    }
}