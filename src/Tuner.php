<?php

namespace Flyokai\Generic;

/**
 * @template T
 */
interface Tuner
{
    /**
     * @param State<T> $state
     * @return State<T>
     */
    public function tune(State $state): State;
}
