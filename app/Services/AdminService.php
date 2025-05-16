<?php

namespace App\Services;

use App\Repositories\AdminRepository;
use Illuminate\Validation\ValidationException;
use Validator;
use Hash;

class AdminService
{
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * 取得資料（條件搜尋、模糊搜尋、關聯、排序、分頁）
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function get($perPage = null)
    {
        $with = $where = $search = [];
        $orderBy = [['id', 'desc']];

        auth('admin')->user()->role != 'develop' ? $where[] = ['role','!=','develop'] : null;

        foreach (request()->all() as $key => $value) {
            if(!in_array($key,['where','with','search','orderBy','perPage','first'])){
                $$key = $value;
            }
        }

        isset($role) && $role != '' ? $where[] = ['role', '=', $role] : null;
        isset($is_on) && $is_on != '' ? $where[] = ['is_on', '=', $is_on] : null;
        isset($name) && $name != '' ? $where[] = ['name', 'like', "%$name%"] : null;
        isset($email) && $email != '' ? $where[] = ['email', 'like', "%$email%"] : null;
        isset($tel) && $tel != '' ? $where[] = ['tel', 'like', "%$tel%"] : null;
        isset($mobile) && $mobile != '' ? $where[] = ['mobile', 'like', "%$mobile%"] : null;

        return $this->adminRepository->get($where, $search, $with, $orderBy, $perPage);
    }

    public function show($id)
    {
        return $this->adminRepository->first($id);
    }

    public function create(array $data)
    {
        return $this->adminRepository->create($data);
    }

    public function update(array $data, $id)
    {

        $admin = $this->show($id);

        // 只在「密碼有填寫」時才檢查
        if (!empty($data['password'])) {
            if (Hash::check($data['password'], $admin->password)) {
                $validator = Validator::make([], []); // 空 Validator
                $validator->errors()->add('password', __('validation.attributes.admin.password_same_as_old'));
                throw new ValidationException($validator);
            }
        }

        return $this->adminRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->adminRepository->delete($id);
    }
}