<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Propagation\MultiResponse;

use OpenTelemetry\Context\ContextInterface;
use OpenTelemetry\Context\Propagation\PropagationSetterInterface;

final class MultiResponsePropagator implements ResponsePropagatorInterface
{
    /** @var list<string> */
    private readonly array $fields;

    /**
     * @no-named-arguments
     *
     * @param list<ResponsePropagatorInterface> $responsePropagators
     */
    public function __construct(
        private readonly array $responsePropagators,
    ) {
        $this->fields = $this->extractFields($this->responsePropagators);
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function inject(&$carrier, ?PropagationSetterInterface $setter = null, ?ContextInterface $context = null): void
    {
        foreach ($this->responsePropagators as $responsePropagator) {
            $responsePropagator->inject($carrier, $setter, $context);
        }
    }

    /**
     * @param list<ResponsePropagatorInterface> $responsePropagators
     * @return list<string>
     */
    private function extractFields(array $responsePropagators): array
    {
        return array_values(
            array_unique(
                // Phan seems to struggle here with the variadic argument
                // @phan-suppress-next-line PhanParamTooFewInternalUnpack
                array_merge(
                    ...array_map(
                        static fn (ResponsePropagatorInterface $responsePropagator) => $responsePropagator->fields(),
                        $responsePropagators
                    )
                )
            )
        );
    }
}
