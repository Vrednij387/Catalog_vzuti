<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL 202 ecommerce
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL 202 ecommerce is strictly forbidden.
 * In order to obtain a license, please contact us: tech@202-ecommerce.com
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe 202 ecommerce
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la SARL 202 ecommerce est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter 202-ecommerce <tech@202-ecommerce.com>
 * ...........................................................................
 *
 * @author    202-ecommerce <tech@202-ecommerce.com>
 * @copyright Copyright (c) 202-ecommerce
 * @license   Commercial license
 */

namespace TotcustomfieldsClasslib\Utils\Dependency;

final class ComposerDependency
{
    /**
     * @var array
     */
    protected static $installed;

    /**
     * @param array $packages
     *
     * @return bool
     *
     * @throws \PrestaShopException
     */
    public function verifyDependencies($packages)
    {
        $installed = $this->getInstalled();

        foreach ($packages as $package) {
            $found = false;
            foreach ($installed['versions'] as $installedPackage => $config) {
                if ($installedPackage == $package) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \PrestaShopException(sprintf('Package %s not found in module, add it via composer, please', $package));
            }
        }

        return true;
    }

    protected function getInstalled()
    {
        if (empty(static::$installed)) {
            $installedPath = _PS_MODULE_DIR_ . 'totcustomfields/vendor/composer/installed.php';
            if (!file_exists($installedPath)) {
                throw new \PrestaShopException('Install composer files before using this module');
            }
            static::$installed = @include $installedPath;
        }

        return static::$installed;
    }
}
