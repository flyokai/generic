<?php

namespace Flyokai\Generic;

/**
 * @template T
 */
interface Builder
{
    /**
     * @return T
     */
    public function build(): mixed;
}
