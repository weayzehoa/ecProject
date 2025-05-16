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

    public function get(array $where = [], array $orderBy = [], array $search = [], int $perPage = null)
    {
        $query = $this->model->newQuery();

        if (!empty($where)) {
            $query->where($where);
        }

        // 🔍 多欄 keyword 搜尋（含 admin 關聯）
        if (!empty($search['keyword'])) {
            $keyword = $search['keyword'];

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('content', 'like', "%{$keyword}%")
                  ->orWhere('url', 'like', "%{$keyword}%");
            });
        }

        foreach ($orderBy as $order) {
            if (!is_array($order) || count($order) !== 2) {
                continue;
            }
            [$column, $direction] = $order;
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($column, $direction);
        }

        return !empty($perPage) ? $query->paginate($perPage) : $query->get();
    }

    public function first($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);
        $this->logModelCreated('新增'.$this->type($model->type), $model);
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
        $model->update($data);
        $original['sort'] != $model->sort ?  $this->sort($model->type) : '';
        $this->logModelChanges('修改'.$this->type($model->type), $model, $original);
        return $model;
    }

    public function delete(int $id)
    {
        $model = $this->model->findOrFail($id);
        $this->logModelDeleted('刪除'.$this->type($model->type), $model);
        return $model->delete();
    }

    public function sort($type)
    {
        $models = $this->model->where('type',$type)->orderBy('sort','asc')->get();
        $i = 1;
        foreach ($models as $model) {
            $model->update(['sort' => $i]);
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