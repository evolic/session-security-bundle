<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests;

use Loculus\SessionSecurityBundle\ValidationManager;
use Loculus\SessionSecurityBundle\Validator\ValidatorInterface;
use Loculus\SessionSecurityBundle\ValidatorChain;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ValidationManagerTest extends TestCase
{
    private ValidatorChain|MockObject $validatorChain;
    private EventDispatcherInterface|MockObject $eventDispatcher;
    private LoggerInterface|MockObject $logger;

    private SessionInterface|MockObject $session;

    private ?ValidatorInterface $browserNameValidator = null;
    private ?ValidatorInterface $browserVersionValidator = null;

    protected function setUp(): void
    {
        $this->validatorChain = $this->getMockBuilder(ValidatorChain::class)
            ->setConstructorArgs([
                $this->getBrowserNameValidator(),
                $this->getBrowserVersionValidator(),
            ])
            ->getMock()
        ;
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->validatorManager = new ValidationManager(
            $this->validatorChain,
            $this->eventDispatcher,
            $this->logger,
        );

        $this->session = $this->createMock(SessionInterface::class);
    }

    public function provideDataForSetupTest(): \Generator
    {
        $data = [];

        $validator1 = $this->getBrowserNameValidator();
        $data[$validator1->getName()] = $validator1->getData();

        yield [
            [$validator1],
            $data,
            2,
        ];

        $validator2 = $this->getBrowserVersionValidator();
        $data[$validator2->getName()] = $validator2->getData();

        yield [
            [$validator1, $validator2],
            $data,
            2,
        ];
    }

    /**
     * @dataProvider provideDataForSetupTest
     */
    public function testSetupWithNoSessionData(
        array $enabledValidators,
        array $data,
        int $counter,
    ): void {
        $this->validatorChain->expects(self::once())
            ->method('setEnabledValidators')
            ->with($enabledValidators)
        ;

        $this->validatorChain->expects(self::once())
            ->method('getEnabledValidators')
            ->willReturn($enabledValidators)
        ;

        $this->session
            ->expects(self::once())
            ->method('has')
            ->with(ValidationManager::SESSION_KEY)
            ->willReturn(false)
        ;

        $this->session
            ->expects(self::once())
            ->method('set')
            ->with(ValidationManager::SESSION_KEY, $data)
        ;

        $this->validatorManager->setup($enabledValidators, $this->session);
    }

    /**
     * @dataProvider provideDataForSetupTest
     */
    public function testSetupWithSessionDataAlreadySet(
        array $enabledValidators,
        array $data,
        int $counter,
    ): void {
        $this->configureSetupWithSessionDataAlreadySet(
            $enabledValidators,
            $data,
            1,
        );

        $this->validatorManager->setup($enabledValidators, $this->session);
    }

    public function configureSetupWithSessionDataAlreadySet(
        array $enabledValidators,
        array $data,
        int $getEnabledValidatorsCounter,
    ): void {
        $this->validatorChain->expects(self::once())
            ->method('setEnabledValidators')
            ->with($enabledValidators)
        ;

        $this->validatorChain->expects(self::exactly($getEnabledValidatorsCounter))
            ->method('getEnabledValidators')
            ->willReturn($enabledValidators)
        ;

        $this->session
            ->expects(self::once())
            ->method('has')
            ->with(ValidationManager::SESSION_KEY)
            ->willReturn(true)
        ;

        $this->session
            ->expects(self::once())
            ->method('get')
            ->with(ValidationManager::SESSION_KEY)
            ->willReturn($data)
        ;
    }

    /**
     * @depends testSetupWithSessionDataAlreadySet
     */
    public function testPassingValidation(): void
    {
        $this->logger->expects(self::never())
            ->method('error')
        ;

        $this->eventDispatcher->expects(self::never())
            ->method('dispatch')
        ;

        $this->validatorManager->validate();
    }

    /**
     * @dataProvider provideDataForSetupTest
     */
    public function testFailingValidation(
        array $enabledValidators,
        array $data,
        int $counter,
    ): void {
        /** @var ValidatorInterface $validator1 */
        $validator1 = $enabledValidators[0];

        self::assertEquals('browser_name_validator', $validator1->getName());

        $validator1->setData('Opera');

        $data[$validator1->getName()] = $validator1->getData();

        $this->configureSetupWithSessionDataAlreadySet(
            $enabledValidators,
            $data,
            $counter,
        );

        $this->validatorManager->setup($enabledValidators, $this->session);

        $this->logger->expects(self::once())
            ->method('debug')
        ;
        $this->logger->expects(self::once())
            ->method('critical')
        ;

        $this->eventDispatcher->expects(self::once())
            ->method('dispatch')
        ;

        $this->validatorManager->validate();
    }

    private function getBrowserNameValidator(): ValidatorInterface
    {
        if (null === $this->browserNameValidator) {
            $this->browserNameValidator = $this->getBrowserNameValidatorObject();
        }

        return $this->browserNameValidator;
    }

    private function getBrowserVersionValidator(): ValidatorInterface
    {
        if (null === $this->browserVersionValidator) {
            $this->browserVersionValidator = $this->getBrowserVersionValidatorObject();
        }

        return $this->browserVersionValidator;
    }

    private function getBrowserNameValidatorObject(): ValidatorInterface
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

    private function getBrowserVersionValidatorObject(): ValidatorInterface
    {
        return new class() implements ValidatorInterface
        {
            private const NAME = 'browser_version_validator';
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
                return '101.0';
            }
        };
    }
}
