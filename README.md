# flyokai/generic

> User docs → [`README.md`](README.md) · Agent quick-ref → [`CLAUDE.md`](CLAUDE.md) · Agent deep dive → [`AGENTS.md`](AGENTS.md)

> Builder, tuner, and execution-pipeline patterns expressed with PHPDoc generics — a tiny but pervasive primitive across Flyokai.

The package gives you four parametric interfaces — `State<T>`, `Builder<T>`, `Tuner<T>`, `Execution<T>` — plus DI-aware containers that compose them. Tuners are how Flyokai lets multiple modules contribute settings to one shared object (an `InputDefinition`, a `Router`, an ACL builder, …) without coupling them.

## Features

- `State<T>` / `Builder<T>` — generic state holder + builder pipeline
- `Tuner<T>` / `TunerContainer<T>` — composable, ordered state mutators
- `Execution<TContext>` / `ExecutionContainer<TContext>` — composable side-effect runners
- `InvalidStateException` — base for builder validation errors
- Fully PHPDoc-`@template` annotated for static analysers

## Installation

```bash
composer require flyokai/generic
```

## Quick start

```php
use Flyokai\Generic\State;
use Flyokai\Generic\BuilderImpl;
use Flyokai\Generic\Tuner;

/** @implements State<\stdClass> */
final class ObjectState implements State
{
    public function __construct(public \stdClass $value) {}
    public function get(): \stdClass { return $this->value; }
}

/** @implements Tuner<\stdClass> */
final class GreetingTuner implements Tuner
{
    public function tune(State $state): State
    {
        $state->value->greeting = 'hello';
        return $state;
    }
}

/** @extends BuilderImpl<\stdClass> */
final class ObjectBuilder extends BuilderImpl {}

$builder = new ObjectBuilder(
    fn() => new ObjectState(new \stdClass()),
    new GreetingTuner(),
);

$obj = $builder->build();   // \stdClass { greeting: "hello" }
```

## Composing tuners

```php
use Flyokai\Generic\TunerContainer;
use Amp\Injector\Composition;

$tuners = new TunerContainer($composition);  // Composition from amphp-injector
$state  = $tuners->tune($state);             // applies every tuner in order
```

`TunerContainer` accepts an `Amp\Injector\Composition` of `Tuner<T>` instances — each module registers its own tuner via DI and ordering (`before`/`after`/`depends`) decides who runs when.

## Executions

`Execution<TContext>` is the side-effect cousin of `Tuner` — it runs against a context but does not return modified state:

```php
use Flyokai\Generic\Execution;
use Flyokai\Generic\ExecutionContainer;

/** @implements Execution<MyCtx> */
final class LogExecution implements Execution
{
    public function execute(mixed $context): void
    {
        $context->logger->info('done');
    }
}

$pipeline = new ExecutionContainer($composition);
$pipeline->execute($context);
```

Subclassing `ExecutionContainer` and overriding `executeChild()` lets you inject hooks (timing, error handling) around every child execution.

## Where it shows up

- `flyokai/symfony-console` — `InputState` is `State<InputInterface>`; `RequiredOptionHandler` and friends are tuners.
- `flyokai/application` — ACL Builder, RouterBuilder, ServerBuilder all expose tuner compositions so modules can extend them.
- `flyokai/data-service`, `flyokai/oauth-server` — `RouterBuilder` middleware and route registration.

## Gotchas

- `BuilderImpl` is `abstract`; you must subclass to constrain the generic parameter.
- Tuner order in a `TunerContainer` is significant — wire ordering carefully via `compositionItem(...)` `before`/`after`/`depends`.
- `ExecutionContainer` does not aggregate return values — they are intentionally `void`.
- PHPDoc `@template` annotations are static-analysis only; nothing enforces the type at runtime.

## See also

- [`flyokai/amphp-injector`](../amphp-injector/README.md) — provides the `Composition` mechanism `TunerContainer` and `ExecutionContainer` rely on.

## License

MIT
