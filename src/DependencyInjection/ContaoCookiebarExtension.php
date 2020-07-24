<?php
/**
 * This file is part of Oveleon Contao Cookiebar.
 *
 * @package     contao-cookiebar
 * @license     AGPL-3.0
 * @author      Daniele Sciannimanica <https://github.com/doishub>
 * @copyright   Oveleon <https://www.oveleon.de/>
 */

namespace Oveleon\ContaoCookiebar\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContaoCookiebarExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yml');

        $arrIframeTypes = [
            'youtube'    => ['ce_youtube'],
            'vimeo'      => ['ce_vimeo'],
            'googlemaps' => ['ce_html_googlemaps'],
        ];

        if(!empty($config['iframe_types']))
        {
            $config['iframe_types'] = array_merge_recursive($arrIframeTypes, $config['iframe_types']);
        }
        else
        {
            $config['iframe_types'] = $arrIframeTypes;
        }

        $container->setParameter('contao_cookiebar.consider_dnt', $config['consider_dnt']);
        $container->setParameter('contao_cookiebar.cookie_lifetime', $config['cookie']['lifetime']);
        $container->setParameter('contao_cookiebar.cookie_token', $config['cookie']['token']);
        $container->setParameter('contao_cookiebar.iframe_types', $config['iframe_types']);
    }
}
