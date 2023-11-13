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

namespace Envoylope\Pcntl\Tests\Functional\Heartbeat;

use AMQPConnection;
use Asmblah\PhpAmqpCompat\AmqpManager;
use Asmblah\PhpAmqpCompat\Configuration\Configuration;
use Envoylope\Pcntl\PcntlSchedulerFactory;
use Envoylope\Pcntl\Tests\Functional\AbstractFunctionalTestCase;
use PhpAmqpLib\Exception\AMQPHeartbeatMissedException;

/**
 * Class ClientHeartbeatTest.
 *
 * Checks connection heartbeat handling when the client fails to send its own heartbeats
 * nor check for server heartbeats on a real connection to a real AMQP broker server
 * when PcntlHeartbeatScheduler is in use.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ClientHeartbeatTest extends AbstractFunctionalTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AmqpManager::setAmqpIntegration(null);
        AmqpManager::setConfiguration(new Configuration(
            schedulerFactory: new PcntlSchedulerFactory()
        ));
    }

    public function tearDown(): void
    {
        parent::tearDown();

        AmqpManager::setAmqpIntegration(null);
        AmqpManager::setConfiguration(null);
    }

    public function testMissedClientHeartbeatIsHandledCorrectly(): void
    {
        $amqpConnection = new AMQPConnection(['heartbeat' => 1]);
        $amqpConnection->connect();

        $this->expectException(AMQPHeartbeatMissedException::class);

        // Use time_sleep_until(...) so that the SIGALRM signals don't prevent the full sleep.
        time_sleep_until(microtime(true) + 5);
    }
}
