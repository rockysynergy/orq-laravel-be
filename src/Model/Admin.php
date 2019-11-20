<?php

namespace Orq\Laravel\Starter\Model;

use App\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use App\Nhqs\Domain\IllegalArgumentException;

/**
 * @Agregate root
 */
class Admin extends OrmModel
{
    protected $table = 'orq_admins';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * name, mobile email and password are stored in users table
     */
    protected static $rules = [
        'club_ids' => 'nullable|min:1|max:20',
        'name' => 'required|max:30',
        'mobile' => 'required|min:11|max:11',
        'email' => 'nullable|max:30|email',
        'user_id' => 'nullable|gte:0',
        // 'password' => 'nullable|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        'password' => 'nullable|min:8|regex:/[A-Za-z\d@$!%*?&]/',
    ];

    protected static $messages = [
        'club_ids.max' => '所属组织id不能超过20个字符',
        'club_ids.min' => '所属组织不能少于1个字符',
        'name.required' => '姓名不能为空!',
        'name.max' => '姓名不能超过30个字符',
        'mobile.required' => '电话不能为空!',
        'mobile.max' => '电话不能少于11个字符',
        'email.max' => '电子邮件不能超过30个字符',
        'mobile.email' => '请提供合法的电子邮件',
        'user_id.gte' => '会员用户id不能小于0',
        'password.min' => '密码至少需要8个字符',
        'password:regex' => '密码需要至少有1个大写字母、1个小写字母、1个数字和1个特殊字符',
    ];

    /**
     * Get associated User record
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function createNew(array $data)
    {
        try {
            self::validate($data);
            $admin = new self();
            if (isset($data['club_ids'])) $admin->club_ids = $data['club_ids'];

            // Create user
            $user = new User();
            foreach (['name', 'email', 'mobile'] as $f) {
                if (isset($data[$f])) $user->$f = $data[$f];
            }
            if (!isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            } else {
                $user->password = Hash::make($data['password']);
            }
            $user->save();
            $admin->user_id = $user->id;
            $admin->save();

            // Assign permissions
            if (isset($data['permissions'])) {
                foreach ($data['permissions'] as $perm) {
                    Permission::firstOrCreate(['name' => $perm]);
                }
                $user->givePermissionTo($data['permissions']);
            }
        } catch (IllegalArgumentException $e) {
            throw $e;
        }
    }

    public function updateInfo(array $data)
    {
        try {
            self::validate($data);
            if (isset($data['club_ids'])) $this->club_ids = $data['club_ids'];
            $this->save();

            // update user
            foreach (['name', 'email', 'mobile'] as $f) {
                if (isset($data[$f])) $this->user->$f = $data[$f];
            }
            if (isset($data['password'])) {
                $this->user->password = Hash::make($data['password']);
            }
            $this->user->save();

            // update permissions
            if (isset($data['permissions'])) {
                foreach ($data['permissions'] as $perm) {
                    Permission::firstOrCreate(['name' => $perm]);
                }
                $this->user->syncPermissions($data['permissions']);
            }
        } catch (IllegalArgumentException $e) {
            throw $e;
        }
    }

    public function deactivate()
    {
        $this->delete();
    }

    public function activate()
    {
        $this->restore();
    }
}
