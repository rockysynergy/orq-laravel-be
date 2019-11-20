<?php

namespace Tests\Functional;

use App\User;
use Tests\TestCase;
use Tests\MakeStrTrait;
use Illuminate\Support\Facades\DB;
use Orq\Laravel\Starter\Model\Admin;
use Spatie\Permission\Models\Permission;
use Orq\Laravel\Starter\Service\AdminService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminServiceTest extends TestCase
{
    use RefreshDatabase;
    use MakeStrTrait;

    /**
     * @test
     */
    public function createNew()
    {
        $data = [
            'name' => 'Judy Gaye',
            'password' => 'Iam1012w',
            'mobile' => '13988766713',
            'permissions' => ['article_manage', 'club_manage', 'activity_manage'],
        ];

        $this->assertEquals(0, DB::table('orq_admins')->get()->count());
        $adminService = resolve(AdminService::class);
        $adminService->createNew($data);

        $user = User::first();
        $this->assertTrue($user->can($data['permissions'][0]));
    }

    /**
     * @test
     */
    public function update()
    {
        $permissions = ['article_manage', 'club_manage', 'activity_manage'];
        foreach ($permissions as $perm) {
            Permission::create(['name'=>$perm]);
        }
        $data = [
            'name' => 'Judy Gaye',
            'password' => 'Iam1012w',
            'mobile' => '13988766713',
        ];
        $user = User::create($data);
        $user->givePermissionTo($permissions);
        $admin = new Admin();
        $admin->user_id = $user->id;
        $admin->save();


        $aData = $data;
        $aData['permissions'] = [$permissions[0], $permissions[1], 'picture_manage'];
        $aData['id'] = $admin->id;
        $aData['name'] = 'The new name';
        $adminService = resolve(AdminService::class);
        $adminService->update($aData);

        $admin = Admin::first();
        $this->assertEquals($aData['name'], $admin->user->name);
        $this->assertFalse($admin->user->can($permissions[2]));
    }

    /**
     * @test
     */
    public function deleteAdmin()
    {
        $data = [
            'name' => 'Judy Gaye',
            'password' => 'Iam1012w',
            'mobile' => '13988766713',
        ];
        $user = User::create($data);
        $admin = new Admin();
        $admin->user_id = $user->id;
        $admin->save();

        $adminService = resolve(AdminService::class);
        $adminService->deactivateAdmin($admin->id);

        $admins = Admin::withTrashed()->get();
        $this->assertNotNull($admins->get(0)->deleted_at);
    }
}
