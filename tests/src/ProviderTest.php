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
class ProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture. This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        // To no call de parent setUp.
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

        $app = new Silex\Application();
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

        $app = new Silex\Application();
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

        $app = new Silex\Application();
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
}
