<?php

declare(strict_types=1);

namespace OpenTelemetry\Tests\Propagation\MultiResponse\Unit;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use OpenTelemetry\Context\Context;
use OpenTelemetry\Contrib\Propagation\MultiResponse\MultiResponsePropagator;
use OpenTelemetry\Contrib\Propagation\MultiResponse\ResponsePropagatorInterface;

class MultiResponsePropagatorTest extends MockeryTestCase
{
    /** @var Mockery\MockInterface&ResponsePropagatorInterface */
    private $propagator1;

    /** @var Mockery\MockInterface&ResponsePropagatorInterface */
    private $propagator2;

    /** @var Mockery\MockInterface&ResponsePropagatorInterface */
    private $propagator3;

    #[\Override]
    protected function setUp(): void
    {
        $this->propagator1 = Mockery::mock(ResponsePropagatorInterface::class);
        $this->propagator2 = Mockery::mock(ResponsePropagatorInterface::class);
        $this->propagator3 = Mockery::mock(ResponsePropagatorInterface::class);

        $this->propagator1->shouldReceive('fields')->andReturn([])->byDefault();
        $this->propagator2->shouldReceive('fields')->andReturn([])->byDefault();
        $this->propagator3->shouldReceive('fields')->andReturn([])->byDefault();
    }

    public function test_fields(): void
    {
        $this->propagator1->allows(['fields' => ['foo', 'bar']]);
        $this->propagator2->allows(['fields' => ['hello', 'world']]);

        $this->assertSame(
            ['foo', 'bar', 'hello', 'world'],
            (new MultiResponsePropagator([$this->propagator1, $this->propagator2]))->fields()
        );
    }

    public function test_fields_duplicates(): void
    {
        $this->propagator1->allows(['fields' => ['foo', 'bar', 'foo']]);
        $this->propagator2->allows(['fields' => ['hello', 'world', 'world', 'bar']]);

        $this->assertSame(
            ['foo', 'bar', 'hello', 'world'],
            (new MultiResponsePropagator([$this->propagator1, $this->propagator2]))->fields()
        );
    }

    public function test_inject_delegates(): void
    {
        $carrier = [];
        $context = Context::getRoot();

        $this->propagator1->expects('inject')->with($carrier, null, $context);
        $this->propagator2->expects('inject')->with($carrier, null, $context);
        $this->propagator3->expects('inject')->with($carrier, null, $context);

        (new MultiResponsePropagator([
            $this->propagator1,
            $this->propagator2,
            $this->propagator3,
        ]))->inject($carrier, null, $context);
    }
}
