<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Validator;

class HttpUserAgentValidator extends AbstractValidator implements ValidatorInterface
{
    protected const NAME = 'user_agent_validator';
    protected const ERROR_MESSAGE_TEMPLATE = 'Expected user agent is not equal to actual "%s"';

    public function __construct()
    {
        $this->data = $this->getActualValue();
    }

    protected function getActualValue(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }
}
