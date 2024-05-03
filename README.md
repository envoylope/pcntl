# Envoylope ext-pcntl

[![Build Status](https://github.com/envoylope/pcntl/workflows/CI/badge.svg)](https://github.com/envoylope/pcntl/actions?query=workflow%3ACI)

Transmits AMQP heartbeats for [Envoylope][Envoylope] using SIGALRM [UNIX System V signals][Signals] via [ext-pcntl][ext-pcntl].

## Why?
`php-amqp`/`librabbitmq` does not fully support [AMQP heartbeats][AMQP heartbeats], they are only supported during [blocking calls into the extension](https://github.com/php-amqp/php-amqp/tree/v1.11.0#persistent-connection).
With `php-amqplib`, we're able to send heartbeats more regularly, using UNIX System V signals.
This library provides its own signal-based heartbeat sender, using `pcntl_async_signals(...)`
to allow for more frequent heartbeat handling, based on the logic in [php-amqplib's sender implementation][php-amqplib's sender].

Note that the `php-fpm` SAPI is _not_ supported by this scheduler, as it does not support the `ext-pcntl` PHP extension.
If you are using `php-fpm`, see [Envoylope EventLoop][Envoylope EventLoop].

## Usage
Install with Composer alongside [php-amqp-compat][php-amqp-compat]:

```shell
$ composer require asmblah/php-amqp-compat
$ composer require envoylope/pcntl
```

### Configuring the bundle

(TODO)

[AMQP heartbeats]: https://www.rabbitmq.com/heartbeats.html
[Envoylope]: https://github.com/envoylope
[Envoylope EventLoop]: https://github.com/envoylope/event-loop
[ext-pcntl]: https://www.php.net/manual/en/book.pcntl.php
[php-amqp-compat]: https://github.com/asmblah/php-amqp-compat
[php-amqplib's sender]: https://github.com/php-amqplib/php-amqplib/blob/v3.5.4/PhpAmqpLib/Connection/Heartbeat/PCNTLHeartbeatSender.php
[Signals]: https://tldp.org/LDP/Linux-Filesystem-Hierarchy/html/signals.html
