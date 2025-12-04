<?php

namespace Flyokai\Generic;

use Amp\Injector\Composition\Composition;

/**
 * @template T
 * @implements Tuner<T>
 */
class TunerContainer implements Tuner
{
    /**
     * @param Composition<string, Tuner<T>> $items
     */
    public function __construct(
        protected Composition $items
    )
    {
    }

    public function tune(State $state): State
    {
        foreach ($this->items as $item) {
            $state = $item->tune($state);
        }
        return $state;
    }
}
