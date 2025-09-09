<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Propagation\MultiResponse;

use OpenTelemetry\Context\ContextInterface;
use OpenTelemetry\Context\Propagation\PropagationSetterInterface;

/**
 * This propagator type is used to inject the trace-context into HTTP responses.
 */
interface ResponsePropagatorInterface
{
    /**
     * Returns list of fields that will be used by this propagator.
     *
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/context/api-propagators.md#fields
     *
     * @return list<string>
     */
    public function fields(): array;
    /**
     * Injects specific values from the provided {@see ContextInterface} into the provided carrier
     * via an {@see PropagationSetterInterface}.
     *
     * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/context/api-propagators.md#textmap-inject
     *
     * @param mixed $carrier
     */
    public function inject(&$carrier, ?PropagationSetterInterface $setter = null, ?ContextInterface $context = null): void;
}
