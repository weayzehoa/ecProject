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

    public function get($where = [])
    {
        return $this->companySettingRepository->get($where);
    }

    public function show($id)
    {
        return $this->companySettingRepository->first($id);
    }
    public function update($id, array $data)
    {
        return $this->companySettingRepository->update($id, $data);
    }
}
