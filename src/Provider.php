<?php
/**
 * NachoNerd Silex Markdown Provider
 * Copyright (C) 2015  Ignacio R. Galieri
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP VERSION 5.4
 *
 * @category  ServiceProvider
 * @package   NachoNerd\Silex\Markdown
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/silex-markdown-provider
 */

namespace NachoNerd\Silex\Markdown;

use Silex\Application;
use Silex\ServiceProviderInterface;
use NachoNerd\Silex\Markdown\Extensions\Markdown;


/**
 * Provider Class
 *
 * @category  ControllerProvider
 * @package   NachoNerd\Silex\Markdown
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/silex-markdown-provider
 */
class Provider implements ServiceProviderInterface
{
    /**
     * Register
     *
     * @param Application $app [description]
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['nn.markdown'] = $app->share(
            function () use ($app) {
                return new Markdown(
                );
            }
        );

        if (isset($app["twig"])) {
            $app['twig'] = $app->share(
                $app->extend(
                    'twig',
                    function ($twig, $app) {
                        $filterParse = new \Twig_SimpleFilter(
                            'markdown_parse',
                            function ($text) use ($app) {
                                return $app['nn.markdown']->parse($text);
                            },
                            array('is_safe' => array('html'))
                        );
                        $twig->addFilter($filterParse);
                        $filterParseFile = new \Twig_SimpleFilter(
                            'markdown_parse_file',
                            function ($filename) use ($app) {
                                return $app['nn.markdown']->parseFile(
                                    $filename
                                );
                            },
                            array('is_safe' => array('html'))
                        );
                        $fucntionParseLastFile = new \Twig_SimpleFunction(
                            'markdown_parse_last_file',
                            function () use ($app) {
                                $content = "";
                                $finderLast = $app['nn.markdown']->getNLastFiles(1);
                                foreach ($finderLast as $file) {
                                    $content = $file->getContents();
                                }
                                return $app['nn.markdown']->parse($content);
                            },
                            array('is_safe' => array('html'))
                        );
                        $twig->addFilter($filterParse);
                        $twig->addFilter($filterParseFile);
                        $twig->addFunction($fucntionParseLastFile);
                        return $twig;
                    }
                )
            );
        }
    }
    /**
     * Boot
     *
     * @param Application $app [description]
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $flavor = 'nn.markdown.flavor';
        $app['nn.markdown']->boot(
            isset($app['nn.markdown.path'])? $app['nn.markdown.path']:'',
            isset($app[$flavor])? $app[$flavor]:'original',
            isset($app['nn.markdown.filter'])? $app['nn.markdown.filter']:'/\.md/'
        );
    }
}
