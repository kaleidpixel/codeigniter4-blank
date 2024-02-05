<?php
namespace KALEIDPIXEL\Utility\Composer;

require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'utility'.DIRECTORY_SEPARATOR.'File.php';

use KALEIDPIXEL\Utility\File;

class CMD {
    public static function fileCopy()
    {
        /**
         * Run.
         */
        $root = dirname(__DIR__, 2);
        $path = [
            [
                'src'  => $root.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'codeigniter4'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'app',
                'dest' => $root.DIRECTORY_SEPARATOR.'app',
                'exc'  => [
                    'welcome_message.php'
                ]
            ],
            [
                'src'  => $root.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'codeigniter4'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'public',
                'dest' => $root.DIRECTORY_SEPARATOR.'public',
            ],
            [
                'src'  => $root.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'codeigniter4'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'tests',
                'dest' => $root.DIRECTORY_SEPARATOR.'tests',
            ],
            [
                'src'  => $root.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'codeigniter4'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'writable',
                'dest' => $root.DIRECTORY_SEPARATOR.'writable',
            ],
            [
                'src'  => $root.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'codeigniter4'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'phpunit.xml.dist',
                'dest' => $root,
            ],
            [
                'src'  => $root.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'codeigniter4'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'spark',
                'dest' => $root,
            ],
        ];

        foreach ($path as $val)
        {
            $file = new File($val['src'], $val['dest'], $val['exc'] ?? []);
            $file->copy();

            unset($file);
        }

        copy($root.DIRECTORY_SEPARATOR.'env', $root.DIRECTORY_SEPARATOR.'.env');
        self::delTree($root.DIRECTORY_SEPARATOR.'scripts');
        self::delStr($root.DIRECTORY_SEPARATOR.'.gitignore', '# kaleidpixel/codeigniter4-blank');

        echo 'Files needed for Codeigniter 4 are copied.';
    }

    public static function delTree(string $dir): bool
    {
        $files = array_diff(scandir($dir), array('.','..'));

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    public static function delStr(string $file, string $keyword)
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        $output = [];

        foreach ($lines as $line) {
            if (trim($line) === $keyword) {
                break;
            }

            $output[] = $line;
        }

        file_put_contents($file, implode("\n", $output));
    }
}
