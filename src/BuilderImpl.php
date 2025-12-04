<?php

namespace Flyokai\Generic;

/**
 * @template T
 * @implements Builder<T>
 */
abstract class BuilderImpl implements Builder
{
    /**
     * @param \Closure():State<T> $stateFactory
     * @param Tuner<T> $tuner
     */
    public function __construct(
        protected \Closure $stateFactory,
        protected Tuner $tuner
    )
    {
    }
    /**
     * @return T
     */
    public function build(): mixed
    {
        $state = ($this->stateFactory)();
        $state = $this->tuner->tune($state);
        return $state->get();
    }
}
