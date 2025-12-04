<?php

namespace Flyokai\Generic;

use Amp\Injector\Composition\Composition;

/**
 * @template TContext
 */
class ExecutionContainer implements Execution
{
    /**
     * @param Composition<string, Execution<TContext>> $items
     */
    public function __construct(
        protected Composition $items
    ) {
    }

    /**
     * @param TContext $context
     * @return void
     */
    public function execute(mixed $context): void
    {
        foreach ($this->items as $item) {
            $this->executeChild($item, $context);
        }
    }

    /**
     * @param Execution<TContext> $step
     * @param TContext $context
     * @return void
     */
    protected function executeChild(Execution $step, mixed $context): void
    {
        $step->execute($context);
    }
}
