<?php

use Illuminate\Contracts\Console\Kernel;

class CommandTest extends TestCase
{
    /**
     *
     */
    public function testCrudMaker()
    {
        /** @var Kernel $console */
        $console = app(Kernel::class);
        $console->handle($input = new \Symfony\Component\Console\Input\ArrayInput([
            'command' => 'crudmaker:new',
            '--no-interaction' => true,
        ]), $output = new \Symfony\Component\Console\Output\BufferedOutput);

        $this->assertContains('Not enough arguments (missing: "table")', $output->fetch());
    }

    public function testCrudTableMaker()
    {
        /** @var Kernel $console */
        $console = app(Kernel::class);
        $console->handle($input = new \Symfony\Component\Console\Input\ArrayInput([
            'command' => 'crudmaker:table',
            '--no-interaction' => true,
        ]), $output = new \Symfony\Component\Console\Output\BufferedOutput);

        $this->assertContains('Not enough arguments (missing: "table")', $output->fetch());
    }
}
