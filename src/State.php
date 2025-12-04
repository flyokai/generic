<?php

namespace Flyokai\Generic;

/**
 * @template T
 */
interface State
{
    /**
     * @return T
     */
    public function get(): mixed;
}
