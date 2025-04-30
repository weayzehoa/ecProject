<?php

namespace App\Repositories;

use App\Models\CompanySetting;
use App\Traits\LoggableRepositoryTrait;

class CompanySettingRepository
{
    use LoggableRepositoryTrait;

    protected $model;

    public function __construct(CompanySetting $model)
    {
        $this->model = $model;
    }

    public function first($id)
    {
        return $this->model->find($id);
    }

    /**
     * 更新資料並回傳模型實體
     *
     * @param array $data
     * @param int $id
     * @return \App\Models\Testing
     */
    public function update(int $id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $original = $model->getOriginal();
        $model->update($data);
        $this->logModelChanges('修改公司資料設定', $model, $original);
        return $model;
    }
}
