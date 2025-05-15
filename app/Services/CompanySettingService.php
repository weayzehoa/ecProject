<?php

namespace App\Services;

use App\Repositories\CompanySettingRepository;

class CompanySettingService
{
    private $companySettingRepository;

    public function __construct(CompanySettingRepository $companySettingRepository)
    {
        $this->companySettingRepository = $companySettingRepository;
    }

    public function show($id)
    {
        return $this->companySettingRepository->first($id);
    }

    public function update(array $data, $id)
    {
        return $this->companySettingRepository->update($id, $data);
    }
}
