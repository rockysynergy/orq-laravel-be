<?php

namespace Orq\Laravel\Starter\Model;

/**
 * @Value (belongs to Member)
 */
class SignLog extends OrmModel
{
    protected $table = 'orq_signlogs';

    /**
     * The attributes that aren't mass assignable.
     *a
     * @var array
     */
    protected $guarded = [];

    public $timestamps = false;

    protected static $rules = [
        'member_id' => 'nullable|gte:0',
    ];

    protected static $messages = [
        'member_id.gte' => '用户id不能是负数',
    ];

    /**
     * Get the Member that owns the Subscription
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
