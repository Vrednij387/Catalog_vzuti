<?php
/**
 * Copyright 2023 ModuleFactory
 *
 * @author    ModuleFactory
 * @copyright ModuleFactory all rights reserved.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class FsMetaGeneratorTools
{
    public static function baseUrl()
    {
        $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
        $base_url .= dirname($_SERVER['SCRIPT_NAME']);
        $base_url = str_replace('\\', '/', $base_url);
        if (Tools::substr($base_url, -1) != '/') {
            $base_url .= '/';
        }

        return $base_url;
    }

    public static function redirectBack($default_back_url = null)
    {
        $base_url = self::baseUrl();

        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $base_url) == 0) {
            $url_back = $_SERVER['HTTP_REFERER'];
        } elseif ($default_back_url) {
            $url_back = $default_back_url;
        } else {
            $url_back = $base_url;
        }

        Tools::redirectLink($url_back);
        exit;
    }

    public static function redirect($url, $headers = null)
    {
        $base_url = Tools::getShopDomainSsl(true) . Context::getContext()->shop->getBaseURI();

        if (!Validate::isAbsoluteUrl($url)) {
            if (Tools::strlen($url) > 0 && Tools::substr($url, 0, 1) == '/') {
                $url = Tools::substr($url, 1);
            }
            $url = $base_url . $url;
        }

        Tools::redirect($url, __PS_BASE_URI__, null, $headers);
    }

    public static function getRequestUri()
    {
        $base_uri = Context::getContext()->shop->getBaseURI();
        $uri = $_SERVER['REQUEST_URI'];
        if ($base_uri != '/') {
            $uri = str_replace($base_uri, '', $_SERVER['REQUEST_URI']);
        }
        if (!$uri) {
            return '/';
        }
        if (Tools::strlen($uri) > 0 && Tools::substr($uri, 0, 1) != '/') {
            return '/' . $uri;
        }

        return $uri;
    }

    public static function getValue($key, $default_value, $from)
    {
        if (isset($from[$key])) {
            return $from[$key];
        }

        return $default_value;
    }

    public static function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, Tools::strlen($needle));
    }

    public static function contains($haystack, $needle)
    {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }

        return false;
    }

    public static function pr($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }

    public static function rnd($length = 10)
    {
        $salt = 'abchefghjkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        srand((float) microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= $length) {
            $num = rand() % 59;
            $tmp = Tools::substr($salt, $num, 1);
            $pass = $pass . $tmp;
            ++$i;
        }

        return $pass;
    }

    public static function removeLineBreaks($string)
    {
        $string = str_replace(["\r\n", "\r"], "\n", $string);
        $lines = explode("\n", $string);
        $new_lines = [];
        foreach ($lines as $line) {
            if (!empty($line)) {
                $new_lines[] = trim($line);
            }
        }

        return implode(' ', $new_lines);
    }

    public static function isSubmitMultilang($submit)
    {
        $return = true;
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            $return = $return && (bool) Tools::isSubmit($submit . '_' . $language['id_lang']);
        }

        return $return;
    }

    public static function cutTextWholeWords($string, $limit)
    {
        $string = trim($string);

        return trim(Tools::substr($string, 0, Tools::strrpos(Tools::substr($string, 0, $limit), ' ')));
    }

    public static function unescapeSmarty($escaped)
    {
        return str_replace(
            ['&amp;', '&quot;', '&#039;', '&lt;', '&gt;'],
            ['&', '"', '\'', '<', '>'],
            $escaped
        );
    }

    public static function minifyCss($params, $css)
    {
        $mode = 'default';
        if (isset($params['mode'])) {
            $mode = $params['mode'];
        }

        if ($mode == 'default') {
            $css = str_replace(': ', ':', $css);
            $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
        }

        return $css;
    }

    public static function slugify($text, string $divider = '-')
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = Tools::strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function getContextLocale(Context $context)
    {
        $locale = $context->getCurrentLocale();
        if (null !== $locale) {
            return $locale;
        }

        $containerFinder = new PrestaShop\PrestaShop\Adapter\ContainerFinder($context);
        $container = $containerFinder->getContainer();
        if (null === $context->container) {
            $context->container = $container;
        }

        $localeRepository = $container->get(Controller::SERVICE_LOCALE_REPOSITORY);

        return $localeRepository->getLocale(
            $context->language->getLocale()
        );
    }

    public static function formatPrice($number, $currencyCode = null)
    {
        if (!is_numeric($number)) {
            return $number;
        }

        $context = Context::getContext();
        $currency = $context->currency;
        $locale = static::getContextLocale($context);
        $currencyCode = $currencyCode ? $currencyCode : $currency->iso_code;

        try {
            return $locale->formatPrice($number, $currencyCode);
        } catch (Exception $e) {
            return $number;
        }
    }
}
