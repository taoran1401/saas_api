<?php
namespace App\Logic\Admin;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginLogic
{
    public static function login($data)
    {
        $admin_user = \App\Model\AdminUserModel::select(['id', 'account', 'password', 'salt'])
            ->where('account', $data['account'])
            ->first();
        if (!$admin_user) {
            //账号不存在
            throw new ApiException("账号或密码不正确！");
        }
        if (!check_password($admin_user->password, $data['password'], $admin_user->salt)) {
            //密码错误
            throw new ApiException("账号或密码不正确！");
        }

        //登录成功
        $admin_user = [
            'admin_user' => [
                'admin_id' => $admin_user->id,
                'admin_name' => $admin_user->name
            ]
        ];
        session()->put('admin_user', $admin_user);
        session()->save();

        return $admin_user;
    }
}