<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GenerateCRUD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate base crud files';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $files;

    private $model_name;

    private $model_path;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('working...');

        $this->model_name = ucfirst(trim($this->argument('model')));
        $this->model_path = "App\Models\\".$this->model_name;

        $this->createModel();
        $this->createPolicy();
        $this->createResources();
        $this->createCreateRequest();
        $this->createUpdateRequest();
        $this->createController();
        $this->createService();
    }

    private function createModel()
    {
        Artisan::call("krlove:generate:model", ['class-name' => $this->model_name, '--output-path' => 'Models', '--namespace' => 'App\Models']);

        $this->line('model generated');
    }

    private function createPolicy()
    {
        $filename = $this->model_name . 'Policy.php';

        $stub = $this->files->get(app_path('Console/Stubs/Policy.stub'));
        $stub = str_replace('MyModelClass', $this->model_name, $stub);
        $stub = str_replace('my_model_instance', snake_case($this->model_name), $stub);

        $this->files->put(app_path('Policies/' . $filename), $stub);

        $this->line('Created Policy ' . $filename);
    }

    private function createResources()
    {
        $filename = "$this->model_name.php";

        $stub = $this->files->get(app_path('Console/Stubs/Resource.stub'));
        $stub = str_replace('MyModelClass', $this->model_name, $stub);
        $stub = str_replace('my_model_instance', snake_case($this->model_name), $stub);

        $attributes = \DB::connection()->getSchemaBuilder()->getColumnListing(str_plural(snake_case($this->model_name)));

        $attribute_string = '[' . "\r\n";

        foreach ($attributes as $attribute) {
            $attribute_string .= "\t\t\t"."'$attribute' => ". '$this->'.$attribute . ", \r\n";
        }

        $attribute_string .= "\t\t];";

        $stub = str_replace('my_attributes', $attribute_string, $stub);

        $this->files->put(app_path('Http/Resources/' . $filename), $stub);
        $this->line('model resource generated');

        Artisan::call("make:resource", ['name' => $this->model_name."Collection", '-c' => true]);
        $this->line('model collection Resource generated');
    }

    private function createCreateRequest()
    {
        File::makeDirectory(app_path("Http/Requests/$this->model_name"));

        $filename = "Create".$this->model_name."Request.php";

        $stub = $this->files->get(app_path('Console/Stubs/CreateRequest.stub'));
        $stub = str_replace('MyModelClass', $this->model_name, $stub);
        $stub = str_replace('my_model_instance', snake_case($this->model_name), $stub);

        $attributes = \DB::connection()->getSchemaBuilder()->getColumnListing(str_plural(snake_case($this->model_name)));

        $attribute_string = '[' . "\r\n";

        foreach ($attributes as $attribute) {
            if (!in_array($attribute, ['id', 'created_at', 'updated_at'])) {
                $attribute_string .= "\t\t\t"."'$attribute' =>  ''". ", \r\n";
            }
        }

        $attribute_string .= "\t\t]";

        $stub = str_replace('my_attributes', $attribute_string, $stub);

        $this->files->put(app_path("Http/Requests/$this->model_name/$filename"), $stub);
        $this->line('create request generated');
    }

    private function createUpdateRequest()
    {
        $filename = "Update".$this->model_name."Request.php";

        $stub = $this->files->get(app_path('Console/Stubs/UpdateRequest.stub'));
        $stub = str_replace('MyModelClass', $this->model_name, $stub);
        $stub = str_replace('my_model_instance', snake_case($this->model_name), $stub);

        $attributes = \DB::connection()->getSchemaBuilder()->getColumnListing(str_plural(snake_case($this->model_name)));

        $attribute_string = '[' . "\r\n";

        foreach ($attributes as $attribute) {
            if (!in_array($attribute, ['id', 'created_at', 'updated_at'])) {
                $attribute_string .= "\t\t\t"."'$attribute' =>  ''". ", \r\n";
            }
        }

        $attribute_string .= "\t\t]";

        $stub = str_replace('my_attributes', $attribute_string, $stub);

        $this->files->put(app_path("Http/Requests/$this->model_name/$filename"), $stub);
        $this->line('update request generated');
    }

    private function createController()
    {
        $filename = $this->model_name . 'Controller.php';

        $stub = $this->files->get(app_path('Console/Stubs/Controller.stub'));
        $stub = str_replace('MyModelClass', $this->model_name, $stub);
        $stub = str_replace('my_model_instance', snake_case($this->model_name), $stub);

        $this->files->put(app_path('Http/Controllers/Api/' . $filename), $stub);

        $this->line('Created Api controller ' . $filename);
    }

    private function createService()
    {
        $filename = $this->model_name . 'Service.php';

        $stub = $this->files->get(app_path('Console/Stubs/Service.stub'));
        $stub = str_replace('MyModelClass', $this->model_name, $stub);
        $stub = str_replace('my_model_instance', snake_case($this->model_name), $stub);

        $this->files->put(app_path('Services/' . $filename), $stub);

        $this->line('Created Service ' . $filename);
    }
}
