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
 * @category  TestCase
 * @package   Tests
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/markdownblog
 */

/**
 * ProviderTest Class
 *
 * @category  TestCase
 * @package   Tests
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/markdownblog
 */
class ProviderTest extends \Silex\WebTestCase
{
    /**
     * Sets up the fixture. This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }
    /**
     * Tears down the fixture. This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
    }

    /**
     * CreateApplication
     *
     * @return \Silex\Application
     */
    public function createApplication()
    {
        $app = new \Silex\Application();
        return $app;
    }

    /**
     * Test Instance Of Provider
     *
     * @return void
     */
    public function testInstanceOfProvider()
    {
        $this->assertInstanceOf(
            'Silex\ServiceProviderInterface',
            new NachoNerd\Silex\Markdown\Provider()
        );
    }

    /**
     * Test Register Markdown
     *
     * @return void
     */
    public function testRegisterMarkdown()
    {

        $app = $this->app;
        $app->register(new \NachoNerd\Silex\Markdown\Provider());

        $this->assertInstanceOf(
            'NachoNerd\Silex\Markdown\Extensions\Markdown',
            $app['nn.markdown']
        );
    }

    /**
     * Test Boot Markdown
     *
     * @return void
     */
    public function testBootMarkdown()
    {

        $app = $this->app;
        $app->register(new \NachoNerd\Silex\Markdown\Provider());
        $app->boot();

        $this->assertInstanceOf(
            'NachoNerd\Silex\Markdown\Extensions\Markdown',
            $app['nn.markdown']
        );
    }

    /**
     * Test Register Markdown Setup
     *
     * @return void
     */
    public function testRegisterMarkdownSetup()
    {

        $app = $this->app;
        $app->register(
            new \NachoNerd\Silex\Markdown\Provider(),
            array(
                "nn.markdown.path" => __DIR__,
                "nn.markdown.flavor" => 'extra',
                "nn.markdown.filter" => '/\.md/'
            )
        );
        $app->boot();

        $markdown = $app['nn.markdown'];

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\Silex\Markdown\Extensions\Markdown'
        );
        $rp = $reflectedObject->getProperty('mdPath');
        $rp->setAccessible(true);

        $this->assertEquals(
            __DIR__,
            $rp->getValue($markdown)
        );

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\Silex\Markdown\Extensions\Markdown'
        );
        $rp = $reflectedObject->getProperty('parser');
        $rp->setAccessible(true);

        $this->assertInstanceOf(
            "\cebe\markdown\MarkdownExtra",
            $rp->getValue($markdown)
        );

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\Silex\Markdown\Extensions\Markdown'
        );
        $rp = $reflectedObject->getProperty('filter');
        $rp->setAccessible(true);

        $this->assertEquals(
            '/\.md/',
            $rp->getValue($markdown)
        );
    }

    /**
     * Test Register Twig And Use Parse
     *
     * @return void
     */
    public function testRegisterTwigAndUseParse()
    {
        $path = realpath(__DIR__."/../resources/")."/";
        $app = $this->app;
        $app->register(
            new \Silex\Provider\TwigServiceProvider(),
            array(
                'twig.path' => $path."views/"
            )
        );
        $app->register(
            new \NachoNerd\Silex\Markdown\Provider(),
            array(
                "nn.markdown.path" => $path,
                "nn.markdown.flavor" => 'extra',
                "nn.markdown.filter" => '/\.md/'
            )
        );
        $app->boot();

        $html = $app['twig']->render(
            "test1.html.twig",
            array('content' => "## Qualibet stirpe")
        );

        $this->assertEquals(
            "<h2>Qualibet stirpe</h2>",
            str_replace("\n", "", $html)
        );
    }

    /**
     * Test Register Twig And Use Parse File
     *
     * @return void
     */
    public function testRegisterTwigAndUseParseFile()
    {
        $path = realpath(__DIR__."/../resources/")."/";
        $app = $this->app;
        $app->register(
            new \Silex\Provider\TwigServiceProvider(),
            array(
                'twig.path' => $path."views/"
            )
        );
        $app->register(
            new \NachoNerd\Silex\Markdown\Provider(),
            array(
                "nn.markdown.path" => $path,
                "nn.markdown.flavor" => 'extra',
                "nn.markdown.filter" => '/\.md/'
            )
        );
        $app->boot();

        $html = $app['twig']->render(
            "test2.html.twig"
        );

        $this->assertEquals(
            "<ol><li>Si nautae modo volucres pampineis silvas leves</li>".
            "<li>Novissima taurorum ille talis cum</li><li>Sublime numina</li>".
            "<li>Sua quae idemque tendebam consumpta nautas</li>".
            "<li>Quid vulnus e positus exierant</li></ol>",
            str_replace("\n", "", $html)
        );
    }

    /**
     * Test Register Twig And Use Parse File
     *
     * @return void
     */
    public function testRegisterTwigAndUseParseLastFile()
    {
        $path = realpath(__DIR__."/../resources/")."/";
        $app = $this->app;
        $app->register(
            new \Silex\Provider\TwigServiceProvider(),
            array(
                'twig.path' => $path."views/"
            )
        );
        $app->register(
            new \NachoNerd\Silex\Markdown\Provider(),
            array(
                "nn.markdown.path" => $path."views/",
                "nn.markdown.flavor" => 'extra',
                "nn.markdown.filter" => '/\.md/'
            )
        );
        $app->boot();

        $html = $app['twig']->render(
            "test3.html.twig"
        );

        $content = "";
        foreach ($app['nn.markdown']->getNLastFiles(1) as $file) {
            $content = $file->getContents();
        }

        $this->assertEquals(
            str_replace("\n", "", $app['nn.markdown']->parse($content)),
            str_replace("\n", "", $html)
        );
    }
}
