<?php

namespace Tests\Functional;

use Tests\TestCase;
use Tests\MakeStrTrait;
use App\Nhqs\Domain\Model\SignLog;
use Illuminate\Support\Facades\DB;
use App\Nhqs\Domain\DomainException;
use Orq\Laravel\Starter\Service\MemberService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberServiceTest extends TestCase
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
            'mobile' => '13988766713',
            'company' => 'The eternal Ltd.',
            'address' => 'LongYuan Road Room 3',
            'email' => 'aa@bb.com',
            'wx_account' => 'The ugly cat',
            'bp_points' => 33,
            'position' => 'Manager',
            'user_id' => '5',
        ];

        $this->assertEquals(0, DB::table('orq_subscriptions')->get()->count());
        $memberService = resolve(MemberService::class);
        $memberService->createNew($data);

        $this->assertDatabaseHas('orq_members', $data);
        $subscription = DB::table('orq_subscriptions')->get()->toArray()[0];
        $this->assertTrue($subscription->member_id > 0);
        $this->assertEquals(1, $subscription->is_first);
    }

    /**
     * @test
     */
    public function updateMemberInfo()
    {
        $data = [
            'id' => 45,
            'name' => 'Judy Gaye',
            'mobile' => '13988766713',
            'company' => 'The eternal Ltd.',
            'address' => 'LongYuan Road Room 3',
            'email' => 'aa@bb.com',
            'wx_account' => 'The ugly cat',
            'bp_points' => 33,
            'position' => 'Manager',
            'user_id' => '5',
        ];
        DB::table('orq_members')->insert($data);
        $memberService = resolve(MemberService::class);
        $nData = $data;
        $nData['name'] = 'The new name';
        $memberService->updateMemberInfo($nData);

        $this->assertDatabaseHas('orq_members', $nData);
    }

    /**
     * @test
     */
    public function deleteMember()
    {
        $data = [
            'id' => 45,
            'name' => 'Judy Gaye',
            'mobile' => '13988766713',
            'company' => 'The eternal Ltd.',
            'address' => 'LongYuan Road Room 3',
            'email' => 'aa@bb.com',
            'wx_account' => 'The ugly cat',
            'bp_points' => 33,
            'position' => 'Manager',
            'user_id' => '5',
        ];
        DB::table('orq_members')->insert($data);
        $memberService = resolve(MemberService::class);
        $memberService->deleteMember($data['id']);

        $data['deleted_at'] = date('Y-m-d H:i:s');
        $this->assertDatabaseHas('orq_members', $data);
    }

    /**
     * @test
     */
    public function approveApplication()
    {
        $data = [
            'id' => 55,
            'is_first' => 1,
        ];
        DB::table('orq_subscriptions')->insert($data);

        $memberService = resolve(MemberService::class);
        $nData = $data;
        $nData['start_time'] = '2019-11-13 13:14:23';
        $nData['end_time'] = '2020-11-13 13:14:23';
        $nData['approve_status'] = 1;
        $memberService->approveApplication($nData);

        $this->assertDatabaseHas('orq_subscriptions', $nData);
    }

    /**
     * @test
     */
    public function BonusPointActions()
    {
        $data = [
            'id' => 45,
            'name' => 'Judy Gaye',
            'mobile' => '13988766713',
            'company' => 'The eternal Ltd.',
            'bp_points' => 400,
            'user_id' => '5',
        ];
        DB::table('orq_members')->insert($data);
        $memberService = resolve(MemberService::class);
        $bpLog_1 = ['amount'=>30, 'note'=>'签到'];
        $bpLog_2 = ['amount'=>-50, 'note'=>'签到'];
        $memberService->addBpLogs(['member_id' => $data['id'], 'logs' => [$bpLog_1, $bpLog_2]]);

        $bpTotal = $memberService->getBpTotal($data['id']);
        $this->assertEquals($data['bp_points'] + $bpLog_1['amount'] + $bpLog_2['amount'], $bpTotal);

        $bpLogs = $memberService->getBpLogs($data['id']);
        $this->assertEquals(2, $bpLogs->count());
        $this->assertEquals($bpLog_1['amount'], $bpLogs->get(0)->amount);
        $this->assertEquals($bpLog_2['amount'], $bpLogs->get(1)->amount);
    }

    /**
     * @test
     */
    public function addAndGetSignLogs()
    {
        $data = [
            'id' => 45,
            'name' => 'Judy Gaye',
            'mobile' => '13988766713',
            'company' => 'The eternal Ltd.',
            'bp_points' => 400,
            'user_id' => '5',
        ];
        DB::table('orq_members')->insert($data);
        $memberService = resolve(MemberService::class);
        $signLog_1 = ['member_id'=>$data['id'], 'created_at' => date('Y-m-d H:i:s')];
        $signLog_2 = ['member_id'=>$data['id'], 'created_at' => date('Y-m-d H:i:s', \strtotime('-1 day'))];
        $memberService->addSignLog($signLog_1);
        $memberService->addSignLog($signLog_2);

        $signLogs = $memberService->getSignLogs($data['id']);
        $this->assertEquals(2, $signLogs->count());
        $this->assertEquals($signLog_1['created_at'], $signLogs->get(0)->created_at);
        $this->assertEquals($signLog_2['created_at'], $signLogs->get(1)->created_at);
    }

    /**
     * @test
     */
    public function doesNotAddDuplicateSignLogs()
    {
        $this->expectException(DomainException::class);
        $data = [
            'id' => 45,
            'name' => 'Judy Gaye',
            'mobile' => '13988766713',
            'company' => 'The eternal Ltd.',
            'bp_points' => 400,
            'user_id' => '5',
        ];
        DB::table('orq_members')->insert($data);
        $memberService = resolve(MemberService::class);
        $signLog_1 = ['member_id'=>$data['id'], 'created_at' => date('Y-m-d H:i:s')];
        $signLog_2 = ['member_id'=>$data['id'], 'created_at' => date('Y-m-d H:i:s')];

        try {
            $memberService->addSignLog($signLog_1);
            $memberService->addSignLog($signLog_2);
        } catch (DomainException $e) {
            throw $e;
        } finally {
            $signLogs = $memberService->getSignLogs($data['id']);
            $this->assertEquals(1, $signLogs->count());
            $this->assertEquals($signLog_1['created_at'], $signLogs->get(0)->created_at);
        }
    }
}
