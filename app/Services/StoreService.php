<?php

namespace App\Services;

use App\Repositories\StoreRepository;
use App\Services\UploadImageService;

class StoreService
{
    protected $storeRepository;

    public function __construct(StoreRepository $storeRepository, UploadImageService $uploadImageService)
    {
        $this->storeRepository = $storeRepository;
        $this->uploadImageService = $uploadImageService;
    }

    public function get($perPage = null)
    {
        $with = $where = $search = [];
        $orderBy = [['sort', 'asc']];

        foreach (request()->all() as $key => $value) {
            if(!in_array($key,['where','with','search','orderBy','perPage','first'])){
                $$key = $value;
            }
        }

        if (request()->filled('keyword')) {
            $search['keyword'] = request('keyword');
        }

        return $this->storeRepository->get($where, $search, $with, $orderBy, $perPage);
    }

    public function show($id)
    {
        return $this->storeRepository->first($id);
    }

    /**
     * 新增資料與圖片
     */
    public function create($request)
    {
        $data = $request->validated();
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $result = $this->uploadImageService->upload(
                $request->file('img'),
                'store'
            );
            if (is_string($result) && str_starts_with($result, 'ERROR:')) {
                return substr($result, 6); // 回傳錯誤訊息給 controller
            }
            $data['img'] = $result;
        }

        return $this->storeRepository->create($data);
    }

    /**
     * 更新資料與圖片
     */
    public function update($request, $id)
    {
        $data = $request->validated();
        $model = $this->storeRepository->first($id);

        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $result = $this->uploadImageService->upload(
                $request->file('img'),
                'store',
                $model->img ?? null
            );
            if (is_string($result) && str_starts_with($result, 'ERROR:')) {
                return substr($result, 6);
            }
            $data['img'] = $result;
        } else {
            unset($data['img']);
        }

        return $this->storeRepository->update($id, $data);
    }

    /**
     * 刪除資料並一併刪除圖片
     */
    public function delete($id)
    {
        $model = $this->storeRepository->first($id);

        if ($model && $model->img) {
            $this->uploadImageService->delete($model->img, 'store');
        }

        return $this->storeRepository->delete($id);
    }

    public function sort($upDown, $id)
    {
        $article = $this->storeRepository->first($id);

        if ($upDown == 'up') {
            $sort = ($article->sort) - 1.5;
        } else {
            $sort = ($article->sort) + 1.5;
        }

        $this->storeRepository->update($id, ['sort' => $sort]);
    }

    public function onOff(array $data, $id)
    {
        return $this->storeRepository->update($id, $data);
    }

    public function delimg(int $id)
    {
        $model = $this->storeRepository->first($id);
        if(!empty($model)){
            !empty($model->img) ? $this->uploadImageService->delete($model->img, 'store') : '';
            return $this->storeRepository->update($id, ['img' => null]);
        }
    }
}