<?php

namespace KALEIDPIXEL\Utility;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class File {
    protected string $source;
    protected string $destination;
    protected array $exclusion;
    protected bool $force;

    public function __construct(string $source, string $destination, array $exclusion = [], bool $force = false)
    {
        $this->source      = $source;
        $this->destination = $destination;
        $this->exclusion   = $exclusion;
        $this->force       = $force;
    }

    public function copy(): bool
    {
        if (file_exists($this->source))
        {
            if (is_file($this->source))
            {
                $destination = $this->getPathAbsolute($this->delete_trailing_slash($this->destination).DIRECTORY_SEPARATOR.basename($this->source));

                if (
                    !in_array(basename($this->source), $this->exclusion, true)
                    || (is_file($destination) && $this->force === true)
                )
                {
                    copy($this->source, $destination);
                }

                return true;
            }

            $iterator = new RecursiveDirectoryIterator($this->source);
            $iterator = new RecursiveIteratorIterator($iterator);

            if (!is_dir($this->destination))
            {
                mkdir($this->destination, 755);
            }

            foreach ($iterator as $info)
            {
                if ($info->isFile())
                {
                    $source      = $info->getPathname();
                    $destination = $this->getPathAbsolute(str_replace($this->source, $this->destination, $source));

                    if (
                        !in_array($info->getFilename(), $this->exclusion, true)
                        && (!is_file($destination) || is_file($destination) && $this->force === true)
                    )
                    {
                        copy($source, $destination);
                    }
                }
                else
                {
                    $destination = $this->getPathAbsolute(str_replace($this->source, $this->destination, $info->getPath()));

                    if (!is_dir($destination))
                    {
                        mkdir($destination, 755);
                    }
                }
            }
        }

        return true;
    }

    /**
     * @see https://www.php.net/manual/en/function.realpath.php#124254 Documentation for php.net
     *
     * @param string $path
     *
     * @return string
     */
    public function getPathAbsolute(string $path): string
    {
        $absolutes = [];
        // Cleaning path regarding OS
        $path = mb_ereg_replace('\\\\|/', DIRECTORY_SEPARATOR, $path, 'msr');
        // Check if path start with a separator (UNIX)
        $startWithSeparator = $path[0] === DIRECTORY_SEPARATOR;
        // Check if start with drive letter
        preg_match('/^[a-z]:/', $path, $matches);
        $startWithLetterDir = $matches[0] ?? false;
        // Get and filter empty sub paths
        $subPaths = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'mb_strlen');

        foreach ($subPaths as $subPath)
        {
            if ('.' === $subPath)
            {
                continue;
            }

            // if $startWithSeparator is false
            // and $startWithLetterDir
            // and (absolutes is empty or all previous values are ..)
            // save absolute cause that's a relative and we can't deal with that and just forget that we want go up
            if ('..' === $subPath
                && !$startWithSeparator
                && !$startWithLetterDir
                && empty(array_filter($absolutes, function ($value)
                {
                    return !('..' === $value);
                }))
            )
            {
                $absolutes[] = $subPath;
                continue;
            }

            if ('..' === $subPath)
            {
                array_pop($absolutes);
                continue;
            }

            $absolutes[] = $subPath;
        }

        $path = (($startWithSeparator ? DIRECTORY_SEPARATOR : $startWithLetterDir) ?
                $startWithLetterDir.DIRECTORY_SEPARATOR : ''
            ).implode(DIRECTORY_SEPARATOR, $absolutes);

        return is_dir($path) ? rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR : $path;
    }

    /**
     * Delete trailing slash.
     *
     * @param string $str
     *
     * @return string
     */
    public function delete_trailing_slash(string $str = ''): string
    {
        return rtrim($str, DIRECTORY_SEPARATOR);
    }
}
