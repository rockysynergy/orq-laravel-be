<?php
namespace Orq\Laravel\Starter\Service;

use Orq\Laravel\Starter\Model\Member;
use Orq\Laravel\Starter\IllegalArgumentException;

class MemberService
{

    /**
     * @throws IllegalArgumentException
     */
    public function createNew(array $data):void
    {
        if (count($data) < 1) throw new IllegalArgumentException('数据不能为空！', 1573528464);

        $member = new Member();
        $member->createNew($data);
    }

    /**
     * @throws IllegalArgumentException
     */
    public function updateMemberInfo(array $data):void
    {
        if (count($data) < 1) throw new IllegalArgumentException('数据不能为空！', 1573635845);
        if (!isset($data['id'])) throw new IllegalArgumentException('请提供记录id！', 1573635901);


        $member = Member::find($data['id']);
        $member->updateInfo($data);
    }

    public function deleteMember(int $memberId)
    {
        $member = Member::find($memberId);
        $member->delete();
    }

    public function approveApplication(array $data)
    {
        if (count($data) < 1) throw new IllegalArgumentException('数据不能为空！', 1573637531);
        if (!isset($data['id'])) throw new IllegalArgumentException('请提供记录id！', 1573637538);

        $member = new Member();
        $member->approveApplication($data);
    }

    public function addBpLogs(array $bpLogs)
    {
        if (!isset($bpLogs['member_id'])) throw new IllegalArgumentException('请提供member_id!', 1573697927);
        if (!isset($bpLogs['logs']) && !is_array($bpLogs['logs'])) throw new IllegalArgumentException('请提供合法的log数据!', 1573700256);
        $member = Member::find($bpLogs['member_id']);

        $member->addBpLogs($bpLogs['logs']);
    }

    public function getBpTotal(int $memberId)
    {
        $member = Member::find($memberId);
        return $member->getBpTotal();
    }

    public function getBpLogs(int $memberId)
    {
        $member = Member::find($memberId);
        return $member->getBpLogs();
    }

    public function addSignLog(array $signLog)
    {
        if (!isset($signLog['member_id'])) throw new IllegalArgumentException('请提供member_id!', 1573720937);
        $member = Member::find($signLog['member_id']);

        $member->addSignLog($signLog);
    }

    public function getSignLogs(int $memberId)
    {
        $member = Member::find($memberId);
        return $member->getSignLogs();
    }
}
