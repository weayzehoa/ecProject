<?php

namespace App\Repositories;

use App\Models\CompanySetting;

class CompanySettingRepository
{
    protected $model;

    public function __construct(CompanySetting $model)
    {
        $this->model = $model;
    }

    public function first($id)
    {
        return $this->model->find($id);
    }

    public function update(int $id, array $data)
    {
        $company = $this->model->findOrFail($id);
        $original = $company->getOriginal();

        $this->logModelChanges('修改公司資料設定', $company, $original);

        return $company;
    }
}
