<?php
namespace Orq\Laravel\Starter\Service;

use Orq\Laravel\Starter\Model\Admin;
use Orq\Laravel\Starter\IllegalArgumentException;

class AdminService
{

    /**
     * @throws IllegalArgumentException
     */
    public function createNew(array $data):void
    {
        if (count($data) < 1) throw new IllegalArgumentException('数据不能为空！', 1573528464);

        $admin = new Admin();
        $admin->createNew($data);
    }

    /**
     * @throws IllegalArgumentException
     */
    public function update(array $data):void
    {
        if (count($data) < 1) throw new IllegalArgumentException('数据不能为空！', 1574146228);
        if (!isset($data['id'])) throw new IllegalArgumentException('请提供记录id！', 1574146275);

        $admin = Admin::find($data['id']);
        $admin->updateInfo($data);
    }

    public function deactivateAdmin(int $adminId):void
    {
        $admin = Admin::find($adminId);
        $admin->deactivate();
    }

    public function activateAdmin(int $adminId):void
    {
        $admin = Admin::withTrashed()->find($adminId);
        $admin->activate();
    }

    public function findAll():array
    {
        $arr = [];
        $result = Admin::withTrashed()->get();
        foreach ($result as $admin) {
            $d = $admin->toArray();
            $d['name'] = $admin->user->name;
            $d['email'] = $admin->user->email;
            $d['mobile'] = $admin->user->mobile;

            array_push($arr, $d);
        }
        return $arr;
    }
}
