<?php

namespace App\Services;

use App\Repositories\ArticleRepository;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function get(
        array $where = [],
        array $search = [],
        array $with = [],
        array $orderBy = [],
        int $perPage = null
    ) {

        foreach (request()->all() as $key => $value) {
            ${$key} = $value;
        }

        if (request()->filled('keyword')) {
            $search = ['name' => request('keyword')];
        }

        return $this->articleRepository->get($where, $search, $with, $orderBy, $perPage);
    }

    public function show($id)
    {
        return $this->articleRepository->first($id);
    }

    public function create(array $data)
    {
        return $this->articleRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->articleRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->articleRepository->delete($id);
    }

    public function sort($type, $upDown, $id)
    {
        $article = $this->articleRepository->first($id);

        if($upDown == 'up') {
            $sort = ($article->sort) - 1.5;
        }else{
            $sort = ($article->sort) + 1.5;
        }
        $this->articleRepository->update($id, ['sort' => $sort]);
    }
}