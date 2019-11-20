<?php

namespace Orq\Laravel\Starter\Model;


class Subscription extends OrmModel
{
    protected $table = 'orq_subscriptions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected static $rules = [
        'start_time' => 'nullable|max:19',
        'end_time' => 'nullable|max:19',
        'pay_status' => 'nullable|in:0,1',
        'pay_amount' => 'nullable|gte:0',
        'is_first' => 'nullable|in:0,1',
        'member_id' => 'nullable|gte:0',
        'approve_status' => 'nullable|in:0,1,2',
    ];

    protected static $messages = [
        'start_time.max' => '开始时间不能超过19个字符',
        'end_time.max' => '结束时间不能超过19个字符',
        'pay_status.in' => '支付状态只能是0或1',
        'pay_amount.gte' => '支付金额不能是负数',
        'is_first.in' => '首次申请只能是0或1',
        'member_id.gte' => '会员id不能是负数',
        'approve_status.in' => '审核状态只能是0,1或2',
    ];

    /**
     * Get the Member that owns the Subscription
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
