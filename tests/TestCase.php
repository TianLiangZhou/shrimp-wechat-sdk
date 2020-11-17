<?php

declare(strict_types=1);

namespace Shrimp\Test;

use Shrimp\ShrimpWechat;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ShrimpWechat
     */
    protected $sdk = null;

    protected function setUp(): void
    {
        $this->sdk  = new ShrimpWechat('wxed1cc1b0e241ff74', '434ca4dfc791853b9ef36ebf24a3ce02');
    }

    protected function tearDown(): void
    {
        $this->sdk = null;
    }
}
