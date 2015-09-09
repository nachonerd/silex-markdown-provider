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

use NachoNerd\Silex\Finder\Extensions\Finder;

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
class MarkdownTest extends \PHPUnit_Framework_TestCase
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
     * ProviderTestConstructor
     *
     * @return array
     */
    public function providerTestConstructor()
    {
        return array(
            array(null, "\cebe\markdown\Markdown", "", '/\.md/'),
            array("", "\cebe\markdown\Markdown", "", '/\.md/'),
            array("original", "\cebe\markdown\Markdown", null, '/\.md/'),
            array("gfm", "\cebe\markdown\GithubMarkdown", '/\.md/', '/\.md/'),
            array("extra", "\cebe\markdown\MarkdownExtra", '/__\.md/', '/__\.md/')
        );
    }

    /**
     * TestConstructor
     *
     * @param string $flavor   [original | gfm | extra]
     * @param string $instance instance of parser
     * @param string $filter   Filter
     * @param string $filterE  Filter Expected
     *
     * @return void
     *
     * @dataProvider providerTestConstructor
     */
    public function testConstructor($flavor, $instance, $filter, $filterE)
    {
        $path = __DIR__;
        $markdown = new \NachoNerd\Silex\Markdown\Extensions\Markdown(
            $path,
            $flavor,
            $filter
        );

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\Silex\Markdown\Extensions\Markdown'
        );
        $rp = $reflectedObject->getProperty('mdPath');
        $rp->setAccessible(true);

        $this->assertEquals(
            $path,
            $rp->getValue($markdown)
        );

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\Silex\Markdown\Extensions\Markdown'
        );
        $rp = $reflectedObject->getProperty('parser');
        $rp->setAccessible(true);

        $this->assertInstanceOf(
            $instance,
            $rp->getValue($markdown)
        );

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\Silex\Markdown\Extensions\Markdown'
        );
        $rp = $reflectedObject->getProperty('filter');
        $rp->setAccessible(true);

        $this->assertEquals(
            $filterE,
            $rp->getValue($markdown)
        );
    }

    /**
     * Test Wrapper Cebe Markdown
     *
     * @return void
     */
    public function testWrapperCebeMarkdown()
    {
        $path = __DIR__;
        $flavor = "original";
        $markdown = new \NachoNerd\Silex\Markdown\Extensions\Markdown(
            $path,
            $flavor
        );

        $reflectedObject = new \ReflectionClass(
            '\NachoNerd\Silex\Markdown\Extensions\Markdown'
        );
        $rp = $reflectedObject->getProperty('parser');
        $rp->setAccessible(true);

        $parser = $rp->getValue($markdown);
        $markdownString = "[![Minimum PHP Version]".
            "(https://img.shields.io/badge/test.svg?style=flat-square)]".
            "(https://php.net/)";

        $this->assertEquals(
            $parser->parse(
                $markdownString
            ),
            $markdown->parse(
                $markdownString
            )
        );
    }

    /**
     * Test Parse File Fail
     *
     * @return void
     */
    public function testParseFileFail()
    {
        $path = __DIR__;
        $flavor = "original";
        $markdown = new \NachoNerd\Silex\Markdown\Extensions\Markdown(
            $path,
            $flavor
        );

        $message = "";

        try {
            $markdown->parseFile("notexitfile.md");
        } catch (\NachoNerd\Silex\Markdown\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            "File ".__DIR__."notexitfile.md Not Found",
            $message
        );
    }

    /**
     * Test Parse File
     *
     * @return void
     */
    public function testParseFile()
    {
        $path = realpath(__DIR__."/../../resources/")."/";
        $flavor = "gfm";
        $markdown = new \NachoNerd\Silex\Markdown\Extensions\Markdown(
            $path,
            $flavor
        );

        $parser = new \cebe\markdown\GithubMarkdown();
        $html = $parser->parse(
            file_get_contents($path."test.md")
        );
        $message = "";
        try {
            $message = $markdown->parseFile("test.md");
        } catch (\NachoNerd\Silex\Markdown\Exceptions\FileNotFound $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $html,
            $message
        );
    }

    /**
     * Test get All Markdown Files
     *
     * @return void
     */
    public function testGetAllMarkdownFiles()
    {
        $path = realpath(__DIR__."/../../resources/")."/";
        $flavor = "gfm";
        $markdown = new \NachoNerd\Silex\Markdown\Extensions\Markdown(
            $path,
            $flavor
        );

        $finder = new Finder();

        $message = "";
        try {
            $message = $markdown->getAllFiles();
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals(
            $finder->files()->name('/\.md/')->in($path),
            $message
        );
    }

    /**
     * ProviderTestGetNLastMarkdownFiles
     *
     * @return void
     */
    public function providerTestGetNLastMarkdownFiles()
    {
        return array(
            array(""),
            array(null),
            array("a"),
            array(1),
            array(2),
            array(4),
            array(5)
        );
    }

    /**
     * Test get N Last Markdown Files
     *
     * @param integer $n eNenesimo number
     *
     * @return void
     *
     * @dataProvider providerTestGetNLastMarkdownFiles
     */
    public function testGetNLastMarkdownFiles($n)
    {
        $path = realpath(__DIR__."/../../resources/")."/";
        $flavor = "gfm";
        $markdown = new \NachoNerd\Silex\Markdown\Extensions\Markdown(
            $path,
            $flavor
        );

        $finder = new Finder();

        $message = "";
        try {
            $message = $markdown->getNLastFiles($n);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        $files = array();
        $j = 0;
        $finder->files()->name('/\.md/')->in($path)->sort(
            function ($a, $b) {
                return ($b->getMTime() - $a->getMTime());
            }
        );
        foreach ($finder as $value) {
            if ($j == $n) {
                break;
            }
            $j++;
            $files[] = $value;
        }

        $finderR = new Finder();
        $finderR->append($files);

        $this->assertEquals(
            $finderR,
            $message
        );
    }
}
