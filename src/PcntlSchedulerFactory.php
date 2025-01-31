<?php

/*
 * Envoylope ext-pcntl heartbeat scheduler.
 * Copyright (c) Dan Phillimore (asmblah)
 * https://github.com/envoylope/pcntl/
 *
 * Released under the MIT license.
 * https://github.com/envoylope/pcntl/raw/main/MIT-LICENSE.txt
 */

declare(strict_types=1);

namespace Envoylope\Pcntl;

use Asmblah\PhpAmqpCompat\Driver\Common\Heartbeat\HeartbeatTransmitterInterface;
use Asmblah\PhpAmqpCompat\Scheduler\Factory\SchedulerFactoryInterface;
use Asmblah\PhpAmqpCompat\Scheduler\Heartbeat\HeartbeatSchedulerInterface;
use Envoylope\Pcntl\Heartbeat\PcntlHeartbeatScheduler;

/**
 * Class PcntlSchedulerFactory.
 *
 * Uses ext-pcntl to allow regular heartbeat scheduling.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class PcntlSchedulerFactory implements SchedulerFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createScheduler(HeartbeatTransmitterInterface $heartbeatTransmitter): HeartbeatSchedulerInterface
    {
        return new PcntlHeartbeatScheduler($heartbeatTransmitter);
    }

    /**
     * Determines whether ext-pcntl -based heartbeat scheduling is supported.
     */
    public static function isSupported(): bool
    {
        return function_exists('pcntl_async_signals');
    }
}
