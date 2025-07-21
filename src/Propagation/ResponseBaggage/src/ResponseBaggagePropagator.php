<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Propagation\ResponseBaggage;

use OpenTelemetry\Context\Context;
use OpenTelemetry\Context\ContextInterface;
use OpenTelemetry\Context\Propagation\ArrayAccessGetterSetter;
use OpenTelemetry\Context\Propagation\PropagationSetterInterface;

final class ResponseBaggagePropagator implements ResponsePropagator
{
    public function inject(mixed &$carrier, ?PropagationSetterInterface $setter = null, ?ContextInterface $context = null): void
    {
        $setter ??= ArrayAccessGetterSetter::getInstance();
        $context ??= Context::getCurrent();
        $ResponseBaggage = ResponseBaggage::fromContext($context);
        if ($ResponseBaggage->isEmpty()) {
            return;
        }
        foreach ($ResponseBaggage->getAll() as $key => $value) {
            $setter->set($carrier, $key, $value);
        }
    }
}
