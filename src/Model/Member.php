<?php

namespace Orq\Laravel\Starter\Model;

use Orq\Laravel\Starter\IllegalArgumentException;

/**
 * @Agregate root
 */
class Member extends OrmModel
{
    protected $table = 'orq_members';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected static $rules = [
        'name' => 'required|max:50',
        'mobile' => 'required|min:11|max:11',
        'company' => 'required|max:100',
        'address' => 'nullable|max:200',
        'email' => 'nullable|max:30',
        'wx_account' => 'nullable|max:30',
        'bp_points' => 'nullable|gte:0',
        'club_ids' => 'nullable|min:1|max:10',
        'position' => 'nullable|min:2|max:15',
        'user_id' => 'nullable|gte:0'
    ];

    protected static $messages = [
        'name.required' => '姓名不能为空!',
        'name.max' => '姓名不能超过50个字符',
        'mobile.required' => '电话不能为空!',
        'mobile.max' => '电话不能少于50个字符',
        'mobile.min' => '电话不能超过50个字符',
        'company.required' => '单位不能为空!',
        'company.max' => '单位不能超过100个字符',
        'address.max' => '地址不能超过200个字符',
        'email.max' => '电子邮件不能超过30个字符',
        'wx_account.max' => '微信号不能超过30个字符',
        'bp_points.gte' => '积分不能为负数',
        'club_ids.max' => '俱乐部id不能超过30个字符',
        'club_ids.min' => '微信号不能少于1个字符',
        'position.max' => '职位不能超过15个字符',
        'position.min' => '职位不能少于2个字符',
        'user_id.gte' => '会员用户id',
    ];

    /**
     * Get associated Subscription records
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get associated BonusPoint records
     */
    public function bpLogs()
    {
        return $this->hasMany(BpLog::class);
    }

    /**
     * Get associated SignLog records
     */
    public function signLogs()
    {
        return $this->hasMany(SignLog::class);
    }

    public function createNew(array $data)
    {
        try {
            Member::validate($data);
            $member = Member::create($data);

            $subscription = new Subscription();
            $subscription->is_first = true;
            $member->subscriptions()->save($subscription);
        } catch (IllegalArgumentException $e) {
            throw $e;
        }
    }

    public function updateInfo(array $data)
    {
        try {
            self::validate($data);
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
            $this->save();
        } catch (IllegalArgumentException $e) {
            throw $e;
        }
    }

    public function approveApplication(array $data)
    {
        try {
            Subscription::validate($data);
            $subscription = Subscription::find($data['id']);
            foreach ($data as $k => $v) {
                $subscription->$k = $v;
            }
            $subscription->save();
        } catch (IllegalArgumentException $e) {
            throw $e;
        }
    }

    public function addBpLogs(array $bpLogs)
    {
        try {
            $nBpLogs = [];
            foreach ($bpLogs as $bpLog) {
                BpLog::validate($bpLog);
                $this->bp_points += $bpLog['amount'];
                array_push($nBpLogs, new BpLog($bpLog));
            }
            $this->save();

            $this->bpLogs()->saveMany($nBpLogs);
        } catch (IllegalArgumentException $e) {
            throw $e;
        }
    }

    public function getBpTotal()
    {
        return $this->bp_points;
    }

    public function getBpLogs()
    {
        return $this->bpLogs;
    }

    public function addSignLog(array $signLog)
    {
        try {
            $cTime = isset($signLog['created_at']) ? $signLog['created_at'] : date('Y-m-d H:i:s');
            $sCount = SignLog::where('member_id', '=', $this->id)
                ->whereDate('created_at', date('Y-m-d', strtotime($cTime)))
                ->count();
            if ($sCount > 0) {
                throw new IllegalArgumentException('已经有今天的签到记录！', 1573723394);
            }
            $this->signLogs()->save(new SignLog($signLog));
        } catch (IllegalArgumentException $e) {
            throw $e;
        }
    }

    public function getSignLogs()
    {
        return $this->signLogs;
    }
}
