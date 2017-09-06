<?php

namespace SwaggerLume\Console;

use Illuminate\Console\Command;
use SwaggerLume\Console\Helpers\Publisher;

class PublishConfigCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'swagger-lume:publish-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish config';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Publish config files');

        (new Publisher($this))->publishFile(
            realpath(__DIR__.'/../../config/').'/swagger-lume.php',
            base_path('config'),
            'swagger-lume.php'
        );
    }
}
