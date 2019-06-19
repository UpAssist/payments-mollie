<?php
namespace UpAssist\Payments\Mollie;

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;

/**
 * Class Package
 * @package UpAssist\Payments\Mollie
 */
class Package extends BasePackage
{
    /**
     * Bootstrap
     *
     * @param Bootstrap $bootstrap
     */
    public function boot(Bootstrap $bootstrap)
    {
        include_once FLOW_PATH_PACKAGES .
            'Libraries/mollie/mollie-api-php/src/Mollie/API/Autoloader.php';
    }
}
