<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Validator;

use Loculus\SessionSecurityBundle\Factory\BrowserFingerprintFactoryInterface;

/**
 * This class requires path to browscap.ini to be set in php.ini
 * It checks if user agent name (Firefox, Chrome, Safari, etc) is valid for current session
 */
class BrowserNameValidator extends AbstractValidator implements ValidatorInterface
{
    protected const NAME = 'browser_name_validator';
    protected const ERROR_MESSAGE_TEMPLATE = 'Expected browser name is not equal to actual "%s"';

    private BrowserFingerprintFactoryInterface $browserFingerprintFactory;

    public function __construct(
        mixed $data = null,
        BrowserFingerprintFactoryInterface $browserFingerprintFactory,
    ) {
        $this->browserFingerprintFactory = $browserFingerprintFactory;

        if ($data === null) {
            $data = $this->getActualValue();
        }

        $this->data = $data;
    }

    protected function getActualValue(): ?string
    {
        return $this->browserFingerprintFactory ->create()?->getBrowserName();
    }
}
