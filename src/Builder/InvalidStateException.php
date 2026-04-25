<?php

namespace Flyokai\Generic\Builder;

abstract class InvalidStateException extends \Exception
{
    public function __construct(
        public readonly string $parameterName,
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
    public static function fromParameter(string $name): static
    {
        return new static(
            $name,
            sprintf('%s config error: missing required parameter "%s"', static::builderClass(), $name)
        );
    }
    /**
     * Factory variant that appends a free-form remediation hint to the standard
     * "missing required parameter X" message. Use this where the bare parameter
     * name doesn't tell the user what config key or CLI flag to set.
     */
    public static function withHelp(string $name, string $help): static
    {
        return new static(
            $name,
            sprintf('%s config error: missing required parameter "%s". %s', static::builderClass(), $name, $help)
        );
    }
    public static function builderClass(): string
    {
        throw new \BadMethodCallException(__METHOD__ . ' should be implemented in child class');
    }
}
