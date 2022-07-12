<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

use Loculus\SessionSecurityBundle\Exception\SessionValidatorNotFoundException;
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
            $found = false;

            foreach ($this->validators as $validator) {
                if ($validator->getName() === $enabledValidatorName) {
                    $this->enabledValidators[] = $validator;

                    $found = true;
                }
            }

            if (!$found) {
                throw new SessionValidatorNotFoundException(
                    sprintf('Cannot enable session validator described as "%s".', $enabledValidatorName)
                );
            }
        }
    }

    public function getEnabledValidators(): array
    {
        return $this->enabledValidators;
    }
}
