<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Validator;

use Loculus\SessionSecurityBundle\Factory\BrowserFingerprintFactoryInterface;

/**
 * This class requires path to browscap.ini to be set in php.ini
 * It checks if user agent platform (e.g. Linux, Windows, iOS, Android, etc) is valid for current session
 */
class BrowserPlatformValidator extends AbstractValidator implements ValidatorInterface
{
    protected const NAME = 'browser_platform_validator';
    protected const ERROR_MESSAGE_TEMPLATE = 'Expected browser platform is not equal to actual "%s"';

    private BrowserFingerprintFactoryInterface $browserFingerprintFactory;

    public function __construct(
        BrowserFingerprintFactoryInterface $browserFingerprintFactory,
    ) {
        $this->browserFingerprintFactory = $browserFingerprintFactory;
        $this->data = $this->getActualValue();
    }

    protected function getActualValue(): ?string
    {
        return $this->browserFingerprintFactory->create()?->getPlatform();
    }
}
