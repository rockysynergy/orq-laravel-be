<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\MakeStrTrait;
use Orq\Laravel\Starter\Model\Member;

class MemberTest extends TestCase
{
    use MakeStrTrait;

    /**
     * @test
     */
    public function validateName()
    {
        $this->expectExceptionMessage('姓名不能为空');
        Member::validate([]);
    }

}
