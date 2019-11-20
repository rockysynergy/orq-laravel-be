<?php

namespace Orq\Laravel\Starter\Model;

/**
 * @Value (belongs to Member)
 */
class BpLog extends OrmModel
{
    protected $table = 'orq_bplogs';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected static $rules = [
        'amount' => 'required|integer',
        'member_id' => 'nullable|gte:0',
        'note' => 'nullable|max:250',
    ];

    protected static $messages = [
        'amount.required' => '积分数量不能为空!',
        'amount.integer' => '积分数量只能是整数',
        'member_id.gte' => '用户id不能是负数',
        'note.max' => '备注不能超过250个字符',
    ];

    /**
     * Get the Member that owns the Subscription
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
