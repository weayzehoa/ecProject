<?php

namespace App\Repositories;

use App\Models\Article;
use App\Traits\LoggableRepositoryTrait;

class ArticleRepository
{
    use LoggableRepositoryTrait;

    protected $model;

    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    public function get(
        array $where = [],
        array $search = [],
        array $with = [],
        array $orderBy = [],
        int $perPage = null
    ) {
        $query = $this->model->newQuery();

        if (!empty($with)) {
            $query->with($with);
        }

        if (!empty($where)) {
            $query->where($where);
        }

        foreach ($search as $field => $keyword) {
            if ($keyword !== '') {
                $query->where($field, 'LIKE', '%' . addcslashes($keyword, '%_') . '%');
            }
        }

        foreach ($orderBy as $order) {
            if (!is_array($order) || count($order) !== 2) {
                continue;
            }
            [$column, $direction] = $order;
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($column, $direction);
        }

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function first($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);
        $type = $this->type($model->type);
        $this->logModelCreated('新增'.$type, $model);
        return $model;
    }

    /**
     * 更新資料並回傳模型實體
     *
     * @param array $data
     * @param int $id
     * @return \App\Models\Article
     */
    public function update(int $id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $original = $model->getOriginal();
        $type = $this->type($model->type);
        $model->update($data);
        $this->sort($model->type);
        $this->logModelChanges('修改'.$type, $model, $original);
        return $model;
    }

    public function delete(int $id)
    {
        $model = $this->model->findOrFail($id);
        $type = $this->type($model->type);
        $this->logModelDeleted('刪除'.$type, $model);
        return $model->delete();
    }

    public function sort($type)
    {
        $articles = $this->model->where('type',$type)->orderBy('sort','asc')->get();
        $i = 1;
        foreach ($articles as $article) {
            $article->update(['sort' => $i]);
            $i++;
        }
    }

    private function type($type)
    {
        switch ($type) {
            case 'news':
                return '最新消息';
            case 'banner':
                return '圖片輪播';
            case 'event':
                return '活動訊息';
            case 'foodmenu':
                return '菜單';
            default:
                return null;
        }
    }
}