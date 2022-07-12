<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Validator;

use Symfony\Component\HttpFoundation\RequestStack;

class RemoteAddressValidator extends AbstractValidator implements ValidatorInterface
{
    protected const NAME = 'ip_address_validator';
    protected const ERROR_MESSAGE_TEMPLATE = 'Expected IP address is not equal to actual "%s"';

    public function __construct(private RequestStack $requestStack)
    {
        $this->data = $this->getActualValue();
    }

    protected function getActualValue(): ?string
    {
        return $this->requestStack->getMainRequest()->getClientIp();
    }
}
