<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

use Loculus\SessionSecurityBundle\Validator\ValidatorInterface;

class ValidatorChain
{
    /**
     * @var array|ValidatorInterface[]
     */
    private array $validators;

    /**
     * @var array|ValidatorInterface[]
     */
    private array $enabledValidators = [];

    public function __construct(ValidatorInterface ...$validators)
    {
        $this->validators = $validators;
    }

    public function setEnabledValidators(array $enabledValidatorNames): void
    {
        foreach ($enabledValidatorNames as $enabledValidatorName) {
            foreach ($this->validators as $validator) {
                if ($validator->getName() === $enabledValidatorName) {
                    $this->enabledValidators[] = $validator;
                }
            }
        }
    }

    public function getEnabledValidators(): array
    {
        return $this->enabledValidators;
    }
}
