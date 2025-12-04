<?php

namespace Flyokai\Generic;

/**
 * @template TContext
 */
interface Execution
{
    /**
     * @param TContext $context
     * @return void
     */
    public function execute(mixed $context): void;
}
