<?php

use org\bovigo\vfs\vfsStream;
use Grafite\CrudMaker\Services\CrudService;
use Illuminate\Console\Command;

class MockProgressBar
{
    public function advance()
    {
        return true;
    }
}

class CrudServiceTest extends TestCase
{
    /**
     * @var CrudService
     */
    protected $service;
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Command
     */
    protected $command;
    protected $bar;

    public function setUp()
    {
        parent::setUp();

        $this->command = Mockery::mock(Command::class);
        $this->command->shouldReceive('callSilent')->andReturnUsing(function ($command, $data) {
            $this->artisan($command, $data);
        });
        $this->bar = Mockery::mock('MockProgressBar')
            ->shouldReceive('advance')
            ->andReturn(true)
            ->getMock();
        $this->service = app(CrudService::class);
        $this->config = [
            'framework'                  => 'Laravel',
            'bootstrap'                  => false,
            'semantic'                   => false,
            'relationships'              => null,
            'schema'                     => null,
            '_path_facade_'              => vfsStream::url('Facades'),
            '_path_service_'             => vfsStream::url('Services'),
            '_path_model_'               => vfsStream::url('Models'),
            '_path_controller_'          => vfsStream::url('Http/Controllers'),
            '_path_api_controller_'      => vfsStream::url('Http/Controllers/Api'),
            '_path_views_'               => vfsStream::url('resources/views'),
            '_path_tests_'               => vfsStream::url('tests'),
            '_path_request_'             => vfsStream::url('Http/Requests'),
            '_path_migrations_'          => 'database/migrations',
            '_path_routes_'              => vfsStream::url('Http/routes.php'),
            '_path_api_routes_'          => vfsStream::url('Http/api-routes.php'),
            'routes_prefix'              => '',
            'routes_suffix'              => '',
            '_namespace_services_'       => 'App\Services',
            '_namespace_facade_'         => 'App\Facades',
            '_namespace_model_'          => 'App\Models',
            '_namespace_controller_'     => 'App\Http\Controllers',
            '_namespace_api_controller_' => 'App\Http\Controllers\Api',
            '_namespace_request_'        => 'App\Http\Requests',
            '_lower_case_'               => strtolower('testTable'),
            '_lower_casePlural_'         => str_plural(strtolower('testTable')),
            '_camel_case_'               => ucfirst(camel_case('testTable')),
            '_camel_casePlural_'         => str_plural(camel_case('testTable')),
            '_ucCamel_casePlural_'       => ucfirst(str_plural(camel_case('testTable'))),
            'template_source'            => __DIR__.'/../../src/Templates/Laravel',
            'options-serviceOnly'        => false,
            'options-apiOnly'            => false,
            'options-withFacade'         => false,
            'options-withBaseService'    => false,
            'options-migration'          => true,
            'options-api'                => true,
            'options-schema'             => 'id:increments,name:string',
        ];
    }

    /**
     * @throws \Exception
     */
    public function testGenerateCore()
    {
        $crud = vfsStream::setup("/");

        $this->service->generateCore($this->config, $this->bar);
        /** @var \org\bovigo\vfs\vfsStreamFile $modelContents */
        $modelContents = $crud->getChild('Models/TestTable.php');
        /** @var \org\bovigo\vfs\vfsStreamFile $serviceContents */
        $serviceContents = $crud->getChild('Services/TestTableService.php');

        $this->assertTrue($crud->hasChild('Services/TestTableService.php'));
        $this->assertContains('class TestTable', $modelContents->getContent());
        $this->assertContains('class TestTableService', $serviceContents->getContent());
    }

    /**
     * @throws \Exception
     */
    public function testGenerateAppBased()
    {
        $crud = vfsStream::setup("/");
        $crud->addChild(vfsStream::newDirectory('Http'));

        $this->service->generateAppBased($this->config, $this->bar);
        /** @var \org\bovigo\vfs\vfsStreamFile $controllerContents */
        $controllerContents = $crud->getChild('Http/Controllers/TestTablesController.php');
        /** @var \org\bovigo\vfs\vfsStreamFile $routesContents */
        $routesContents = $crud->getChild('Http/routes.php');

        $this->assertTrue($crud->hasChild('Http/Controllers/TestTablesController.php'));
        $this->assertContains('class TestTablesController', $controllerContents->getContent());
        $this->assertContains('TestTablesController', $routesContents->getContent());
    }

    /**
     * @throws \Exception
     */
    public function testGenerateAPI()
    {
        $crud = vfsStream::setup("/");
        $crud->addChild(vfsStream::newDirectory('Http'));

        $this->service->generateAPI($this->config, $this->bar);
        /** @var \org\bovigo\vfs\vfsStreamFile $controllerContents */
        $controllerContents = $crud->getChild('Http/Controllers/Api/TestTablesController.php');
        /** @var \org\bovigo\vfs\vfsStreamFile $routesContents */
        $routesContents = $crud->getChild('Http/api-routes.php');

        $this->assertTrue($crud->hasChild('Http/Controllers/Api/TestTablesController.php'));
        $this->assertContains('class TestTablesController', $controllerContents->getContent());
        $this->assertContains('TestTablesController', $routesContents->getContent());
    }
}
