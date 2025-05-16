<?php

namespace App\Services;

use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use App\Services\UploadImageService;

class ArticleService
{
    protected $articleRepository;
    protected $uploadImageService;

    public function __construct(ArticleRepository $articleRepository, UploadImageService $uploadImageService)
    {
        $this->articleRepository = $articleRepository;
        $this->uploadImageService = $uploadImageService;
    }

    public function get($perPage = null, array $with = [], array $where = [], array $orderBy = [['sort', 'asc']], array $search = [], bool $first = false)
    {
        foreach (request()->all() as $key => $value) {
            if(!in_array($key,['where','with','search','orderBy','perPage','first'])){
               ${$key} = $value;
            }
        }

        if (request()->filled('keyword')) {
            $search['keyword'] = request('keyword');
        }

        return $this->articleRepository->get($where, $search, $with, $orderBy, $perPage, $first);
    }

    public function show($id)
    {
        return $this->articleRepository->first($id);
    }

    /**
     * 新增資料與圖片
     */
    public function create(Request $request)
    {
        $data = $request->validated();
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $result = $this->uploadImageService->upload(
                $request->file('img'),
                $data['type'] ?? 'default'
            );
            if (is_string($result) && str_starts_with($result, 'ERROR:')) {
                return substr($result, 6); // 回傳錯誤訊息給 controller
            }
            $data['img'] = $result;
        }

        return $this->articleRepository->create($data);
    }

    /**
     * 更新資料與圖片
     */
    public function update(Request $request, $id)
    {
        $data = $request->validated();
        $model = $this->articleRepository->first($id);

        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $result = $this->uploadImageService->upload(
                $request->file('img'),
                $data['type'] ?? 'default',
                $model->img ?? null
            );
            if (is_string($result) && str_starts_with($result, 'ERROR:')) {
                return substr($result, 6);
            }
            $data['img'] = $result;
        } else {
            unset($data['img']);
        }

        return $this->articleRepository->update($id, $data);
    }

    /**
     * 刪除資料並一併刪除圖片
     */
    public function delete($id)
    {
        $model = $this->articleRepository->first($id);

        if ($model && $model->img) {
            $this->uploadImageService->delete($model->img, $model->type ?? 'default');
        }

        return $this->articleRepository->delete($id);
    }

    public function sort($type, $upDown, $id)
    {
        $article = $this->articleRepository->first($id);

        if ($upDown == 'up') {
            $sort = ($article->sort) - 1.5;
        } else {
            $sort = ($article->sort) + 1.5;
        }

        $this->articleRepository->update($id, ['sort' => $sort]);
    }

    public function onOff(array $data, $id)
    {
        return $this->articleRepository->update($id, $data);
    }

    public function delimg(int $id)
    {
        $model = $this->articleRepository->first($id);
        if(!empty($model)){
            !empty($model->img) ? $this->uploadImageService->delete($model->img, $model->type ?? 'default') : '';
            return $this->articleRepository->update($id, ['img' => null, 'is_on' => 0]);
        }
    }
}
