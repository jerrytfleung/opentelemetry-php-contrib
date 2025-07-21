<?php

declare(strict_types=1);

namespace OpenTelemetry\Tests\Propagation\ResponseBaggage\Unit;

use OpenTelemetry\Context\Context;
use OpenTelemetry\Contrib\Propagation\ResponseBaggage\ResponseBaggage;
use OpenTelemetry\Contrib\Propagation\ResponseBaggage\ResponseBaggagePropagator as Propagator;
use PHPUnit\Framework\TestCase;

class PropagatorTest extends TestCase
{

    /**
     * @test
     * Injects with a valid traceId, spanId, and is sampled
     * restore(string $traceId, string $spanId, bool $sampled = false, bool $isRemote = false, ?API\TraceState $traceState = null): SpanContext
     */
    public function test_inject_valid_sampled_trace_id()
    {
        $carrier = [];
        (new Propagator())->inject(
            $carrier,
            null,
            Context::getCurrent()->withContextValue(ResponseBaggage::getBuilder()->set('k1', 'v1')->set('k2', 'v2')->build())
        );
        $this->assertSame(['k1' => 'v1', 'k2' => 'v2'], $carrier);
    }

    //    /**
    //     * @test
    //     * Injects with a valid traceId, spanId, and is not sampled
    //     */
    //    public function test_inject_valid_not_sampled_trace_id()
    //    {
    //        $carrier = [];
    //        (new Propagator())->inject(
    //            $carrier,
    //            null,
    //            $this->withSpanContext(
    //                SpanContext::create(self::TRACE_ID, self::SPAN_ID),
    //                Context::getCurrent()
    //            )
    //        );
    //
    //        $this->assertSame(
    //            [Propagator::TRACERESPONSE => self::TRACERESPONSE_HEADER_NOT_SAMPLED],
    //            $carrier
    //        );
    //    }
    //
    //    /**
    //     * @test
    //     * Test inject with tracestate - note: tracestate is not a part of traceresponse
    //     */
    //    public function test_inject_trace_id_with_trace_state()
    //    {
    //        $carrier = [];
    //        (new Propagator())->inject(
    //            $carrier,
    //            null,
    //            $this->withSpanContext(
    //                SpanContext::create(self::TRACE_ID, self::SPAN_ID, TraceFlags::SAMPLED, new TraceState('vendor1=opaqueValue1')),
    //                Context::getCurrent()
    //            )
    //        );
    //
    //        $this->assertSame(
    //            [Propagator::TRACERESPONSE => self::TRACERESPONSE_HEADER_SAMPLED],
    //            $carrier
    //        );
    //    }
    //
    //    /**
    //     * @test
    //     * Test with an invalid spanContext, should return null
    //     */
    //    public function test_inject_trace_id_with_invalid_span_context()
    //    {
    //        $carrier = [];
    //        (new Propagator())->inject(
    //            $carrier,
    //            null,
    //            $this->withSpanContext(
    //                SpanContext::create(SpanContextValidator::INVALID_TRACE, SpanContextValidator::INVALID_SPAN, TraceFlags::SAMPLED, new TraceState('vendor1=opaqueValue1')),
    //                Context::getCurrent()
    //            )
    //        );
    //
    //        $this->assertEmpty($carrier);
    //    }

}
