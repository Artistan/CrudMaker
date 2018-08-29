<?php

namespace Grafite\CrudMaker\Services;

use Illuminate\Filesystem\Filesystem;

class FileService
{
    /**
     * make directory
     *
     * @param $path
     * @param $mode
     * @param $recursive
     */
    public function mkdir($path, $mode, $recursive)
    {
        if (! is_dir($path)) {
            mkdir($path, $mode, $recursive);
        }
    }

    /**
     * get file contents
     *
     * @param $file
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get($file)
    {
        $filesystem = new Filesystem();
        $templateSource = config('crudmaker.template_source');
        $orginalFileSource = __DIR__.'/../Templates/Laravel/';

        if (is_null($templateSource)) {
            $templateSource = base_path('resources/crudmaker');
        }

        if (! file_exists($file)) {
            $file = str_replace($templateSource, $orginalFileSource, $file);
        }

        return $filesystem->get($file);
    }
}
