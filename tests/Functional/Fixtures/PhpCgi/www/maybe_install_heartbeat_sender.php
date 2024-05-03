<?php

declare(strict_types=1);

use Asmblah\PhpAmqpCompat\Bridge\Connection\AmqpConnectionBridgeInterface;
use Asmblah\PhpAmqpCompat\Driver\Amqplib\Heartbeat\HeartbeatTransmitter;
use Asmblah\PhpAmqpCompat\Heartbeat\HeartbeatSender;
use Asmblah\PhpAmqpCompat\Misc\Clock;
use Envoylope\Pcntl\Heartbeat\PcntlHeartbeatScheduler;

require_once dirname(__DIR__, 5) . '/vendor/autoload.php';

$heartbeatInterval = isset($_POST['heartbeat_interval']) ? (int)$_POST['heartbeat_interval'] : null;
$sleepDuration = isset($_POST['sleep_duration']) ? (int)$_POST['sleep_duration'] : null;

if ($heartbeatInterval !== null) {
    $heartbeatSender = new HeartbeatSender(
        new PcntlHeartbeatScheduler(
            new HeartbeatTransmitter(new Clock())
        )
    );

    $heartbeatSender->register(mock(AmqpConnectionBridgeInterface::class, [
        'getHeartbeatInterval' => $heartbeatInterval,
    ]));

    print 'Installed heartbeat sender with ' . $heartbeatInterval . ' second interval' . PHP_EOL;
} else {
    print 'Did not install heartbeat sender' . PHP_EOL;
}

if ($sleepDuration !== null) {
    sleep($sleepDuration);

    print 'Slept for ' . $sleepDuration . ' seconds' . PHP_EOL;
} else {
    print 'Did not sleep' . PHP_EOL;
}
