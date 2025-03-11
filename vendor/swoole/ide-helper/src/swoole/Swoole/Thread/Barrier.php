<?php

declare(strict_types=1);

namespace Swoole\Thread;

/**
 * Class \Swoole\Thread\Barrier.
 *
 * This class is available only when PHP is compiled with Zend Thread Safety (ZTS) enabled and Swoole is installed with
 * the "--enable-swoole-thread" configuration option.
 *
 * @since 6.0.0
 */
final class Barrier
{
    public function __construct(int $count)
    {
    }

    public function wait(): void
    {
    }
}
