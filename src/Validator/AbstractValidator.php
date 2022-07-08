<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Validator;

abstract class AbstractValidator
{
    protected const NAME = 'abstract_validator';
    protected const ERROR_MESSAGE_TEMPLATE = 'Expected value is not equal to actual "%s"';

    protected ?string $data;
    protected string $errorMessage;

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        $actual = $this->getActualValue();

        $this->errorMessage = sprintf(static::ERROR_MESSAGE_TEMPLATE, $actual);

        return $this->getData() === $actual;
    }

    /**
     * @inheritDoc
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    abstract protected function getActualValue(): ?string;
}
