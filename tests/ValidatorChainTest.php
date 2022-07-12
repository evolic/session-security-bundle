<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

        $validatorChain->setEnabledValidators(['browser_name_validator']);

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
            private const NAME = 'browser_name_validator';
            private const ERROR_MESSAGE_TEMPLATE = 'Expected value is not equal to actual "%s"';

            private ?string $data;
            private string $errorMessage;

            public function __construct(mixed $data = null)
            {
                if ($data === null) {
                    $data = $this->getActualValue();
                }

                $this->data = $data;
            }

            public function isValid(): bool
            {
                $actual = $this->getActualValue();

                $this->errorMessage = sprintf(static::ERROR_MESSAGE_TEMPLATE, $actual);

                return $this->getData() === $actual;
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

            public function getErrorMessage(): string
            {
                return $this->errorMessage;
            }

            private function getActualValue(): ?string
            {
                return 'Firefox';
            }
        };
    }
}
