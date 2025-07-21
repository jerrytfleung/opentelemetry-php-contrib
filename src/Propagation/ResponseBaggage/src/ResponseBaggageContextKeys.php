<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Propagation\ResponseBaggage;

use OpenTelemetry\Context\Context;
use OpenTelemetry\Context\ContextKeyInterface;

final class ResponseBaggageContextKeys
{
    public static function responsebaggage(): ContextKeyInterface
    {
        static $instance;

        return $instance ??= Context::createKey('response-baggage');
    }
}
