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
 * @package   NachoNerd\Silex\Markdown\Worker
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/silex-markdown-provider
 */

namespace NachoNerd\Silex\Markdown\Extensions;
use NachoNerd\Silex\Finder\Extensions\Finder;

/**
 * Provider Class
 *
 * @category  ControllerProvider
 * @package   NachoNerd\Silex\Markdown\Worker
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2015 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      https://github.com/nachonerd/silex-markdown-provider
 */
class Markdown
{
    /**
     * Markdown Folder Path
     *
     * @var string
     */
    protected $mdPath = "";

    /**
     * Filter Markdown Files
     *
     * @var string
     */
    protected $filter = "";

    /**
     * Markdown Parser
     *
     * @var cebe\markdown\Parser
     */
    protected $parser = null;

    /**
     * Constructor
     *
     * @param string $path   Markdown Folder Path
     * @param string $flavor [original | gfm | extra]
     * @param string $filter Filter Markdown Files
     */
    public function __construct(
        $path = '',
        $flavor = "original",
        $filter = '/\.md/'
    ) {
        $this->boot($path, $flavor, $filter);
    }

    /**
     * Boot
     *
     * @param string $path   Markdown Folder Path
     * @param string $flavor [original | gfm | extra]
     * @param string $filter Filter Markdown Files
     *
     * @return void
     */
    public function boot(
        $path = '',
        $flavor = "original",
        $filter = '/\.md/'
    ) {
        $this->mdPath = $path;
        switch ($flavor) {
        case 'extra':
            $parser = new \cebe\markdown\MarkdownExtra();
            break;
        case 'gfm':
            $parser = new \cebe\markdown\GithubMarkdown();
            break;
        default:
        case 'original':
            $parser = new \cebe\markdown\Markdown();
            break;
        }
        $this->parser = $parser;
        if (empty($filter)) {
            $this->filter = '/\.md/';
        } else {
            $this->filter = $filter;
        }
    }

    /**
     * Parses the given text considering the full language.
     *
     * This includes parsing block elements as well as inline elements.
     *
     * @param string $text the text to parse
     *
     * @return string
     */
    public function parse($text)
    {
        return $this->parser->parse($text);
    }

    /**
     * Parses the given file
     *
     * @param string $fileToParse File Name To Parse
     *
     * @return string
     *
     * @throws \NachoNerd\Silex\Markdown\Exceptions\FileNotFound
     */
    public function parseFile($fileToParse)
    {
        $filename = $this->mdPath.$fileToParse;
        if (file_exists($filename)) {
            return $this->parse(file_get_contents($filename));
        }

        throw new \NachoNerd\Silex\Markdown\Exceptions\FileNotFound(
            sprintf("File %s Not Found", $filename),
            1
        );
    }

    /**
     * Give All Files in directory given using filer given
     *
     * @return Symfony\Component\Finder\Finder
     */
    public function getAllFiles()
    {
        $finder = new Finder();
        return $finder->files()->name($this->filter)->in($this->mdPath);
    }

    /**
     * Give N Last Files in directory given using filer given
     *
     * @param integer $n Umpteenth Number
     *
     * @return Symfony\Component\Finder\Finder
     */
    public function getNLastFiles($n)
    {
        $finder = new Finder();
        $finder->files()->name(
            $this->filter
        )->in(
            $this->mdPath
        )->sortByModifiedTimeDesc();
        return $finder->getNFirst($n);
    }
}
