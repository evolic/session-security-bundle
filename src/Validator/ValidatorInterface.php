<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Validator;

interface ValidatorInterface
{
    /**
     * This method will be called at the beginning of every session to determine
     * if the current environment matches that which was store in the setup() procedure.
     */
    public function isValid(): bool;

    /**
     * Gets data from validator to be used for validation comparisons
     */
    public function getData(): mixed;

    /**
     * Gets validator name for use with storing validators between requests
     */
    public function getName(): string;
}
