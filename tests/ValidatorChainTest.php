<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests;

use Loculus\SessionSecurityBundle\Exception\SessionValidatorNotFoundException;
use Loculus\SessionSecurityBundle\Validator\ValidatorInterface;
use Loculus\SessionSecurityBundle\ValidatorChain;
use PHPUnit\Framework\TestCase;

class ValidatorChainTest extends TestCase
{
    public function testConstructingValidatorChainWithNoData(): void
    {
        $validatorChain = new ValidatorChain();

        self::assertEquals([], $validatorChain->getEnabledValidators());
    }

    public function testConstructingValidatorChainWithNoDataAndEmptyListOfEnabledValidators(): void
    {
        $validatorChain = new ValidatorChain();

        $validatorChain->setEnabledValidators([]);

        self::assertEquals([], $validatorChain->getEnabledValidators());
    }

    public function testConstructingValidatorChainWithAnonymousValidator(): void
    {
        $validator = $this->getBrowserNameValidatorClass();

        $validatorChain = new ValidatorChain($validator);

        $validatorChain->setEnabledValidators(['browser_nane_validator']);

        $enabledValidators = $validatorChain->getEnabledValidators();

        foreach ($enabledValidators as $enabledValidator) {
            self::assertInstanceOf(ValidatorInterface::class, $enabledValidator);
        }
    }

    public function testEnablingValidatorWhichIsUnknownToValidatorChain(): void
    {
        self::expectException(SessionValidatorNotFoundException::class);

        $validator = $this->getBrowserNameValidatorClass();

        $validatorChain = new ValidatorChain($validator);

        $validatorChain->setEnabledValidators(['unknown_validator']);
    }

    private function getBrowserNameValidatorClass(): ValidatorInterface
    {
        return new class() implements ValidatorInterface
        {
            private const NAME = 'browser_nane_validator';

            private ?string $data;

            public function __construct(mixed $data = null)
            {
                if ($data === null) {
                    $data = $this->getBrowserName();
                }

                $this->data = $data;
            }

            public function isValid(): bool
            {
                return $this->getData() === $this->getBrowserName();
            }

            public function getData(): mixed
            {
                return $this->data;
            }

            public function setData(mixed $data): void
            {
                $this->data = $data;
            }

            public function getName(): string
            {
                return self::NAME;
            }

            private function getBrowserName(): ?string
            {
                return 'Firefox';
            }
        };
    }
}
