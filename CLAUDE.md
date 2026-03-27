# flyokai/generic

PHP generics/strong typing with builder, tuner, and execution pipeline patterns using `@template` PHPDoc.

See [AGENTS.md](AGENTS.md) for detailed module knowledge.

## Quick Reference

- **State\<T\>**: Generic container, `get(): T`
- **Builder\<T\>**: Pipeline: stateFactory → tune → get
- **Tuner\<T\>**: Composable state modifier
- **Execution\<T\>**: Composable side-effect runner
- **Depends on**: `wtsergo/amphp-injector` (Composition class)
