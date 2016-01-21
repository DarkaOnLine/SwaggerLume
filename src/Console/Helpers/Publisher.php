<?php

namespace SwaggerLume\Console\Helpers;

use Illuminate\Console\Command;

class Publisher
{
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function publishFile($source, $destinationPath, $fileName)
    {
        if (! is_dir($destinationPath)) {
            if (! mkdir($destinationPath, 0755, true)) {
                $this->command->error('Cant crate directory: '.$destinationPath);
            }
        }

        if (! is_writable($destinationPath)) {
            if (! chmod($destinationPath, 0755)) {
                $this->command->error('Destination path is not writable');
            }
        }

        if (file_exists($source)) {
            if (! copy($source, $destinationPath.'/'.$fileName)) {
                $this->command->error('File was not copied');
            }
        } else {
            $this->command->error('Source file does not exists');
        }
    }

    public function publishDirectory($source, $destination)
    {
        if (! is_dir($source)) {
            $this->command->error('Bad source path');
        } else {
            $dir = opendir($source);

            if (! is_dir($destination)) {
                if (! mkdir($destination, 0755, true)) {
                    $this->command->error('Cant crate directory: '.$destination);
                }
            }

            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($source.'/'.$file)) {
                        $this->publishDirectory($source.'/'.$file, $destination.'/'.$file);
                    } else {
                        copy($source.'/'.$file, $destination.'/'.$file);
                    }
                }
            }
            closedir($dir);
        }
    }
}
