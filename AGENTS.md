# flyokai/generic

> User docs → [`README.md`](README.md) · Agent quick-ref → [`CLAUDE.md`](CLAUDE.md) · Agent deep dive → [`AGENTS.md`](AGENTS.md)

PHP generics/strong typing support using PHPDoc `@template` syntax. Provides builder, tuner, and execution pipeline patterns.

## Key Abstractions

### State\<T\> (Interface)
Generic state container. Method: `get(): mixed` (returns value of type T).

### Builder\<T\> (Interface) / BuilderImpl\<T\> (Abstract)
Builder pipeline: `stateFactory → tuner.tune(state) → state.get()`.
- BuilderImpl takes `Closure():State<T>` and `Tuner<T>` in constructor
- `build()` creates state, tunes it, returns the value

### Tuner\<T\> (Interface) / TunerContainer\<T\>
- `tune(State<T>): State<T>` — modifies state in pipeline
- TunerContainer aggregates multiple tuners via `Composition` (from amphp-injector), applies sequentially

### Execution\<TContext\> (Interface) / ExecutionContainer\<TContext\>
- `execute(mixed $context): void` — side-effect execution
- ExecutionContainer aggregates executions, runs sequentially
- `executeChild()` is extensible hook for child execution

### InvalidStateException (Abstract)
Base for builder validation errors. Requires child to implement `builderClass(): string`.
Factory: `fromParameter(string)`.

## Integration

- Depends on `flyokai/amphp-injector` for Composition class
- Used by `flyokai/symfony-console` (InputState implements State\<InputInterface\>)
- Foundation for builder/pipeline/composition patterns across the ecosystem

## Gotchas

- BuilderImpl is abstract — cannot instantiate directly
- Tuner order in TunerContainer matters (sequential application)
- ExecutionContainer doesn't aggregate return values (void)
- PHPDoc templates are for static analysis only — no runtime enforcement
