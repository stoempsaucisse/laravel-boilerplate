<?php namespace Stoempsaucisse\LaravelBoilerplate\Console\Commands;

use Illuminate\Console\Command;

class BoilerplateServiceProviderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boilerplate:resource-sp
                                {resourceName : The ResourceName.}
                                {app=Stoempsaucisse : The app Name (Stoempsaucisse).}
                                {--is-viewable-resource : Whether the ServiceProvider concerns a resource that has it\'s own create and update forms.}
                                {--no-tests : Do not populate the tests directory with stubs.}
                                {--no-views : Do not populate the views directory.}
                                {--namespace= : Complete/Namespace to use. This overrides Namespace automatic definition (App\Resources).}
                                {--contract-ext= : Namespaced Interface the Contract extends.}
                                {--handler-imp= : Namespaced Interface the ResourceHandler implements.}
                                {--rn-plural= : The plural of the ResourceName.}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a ServiceProvider for a resource with it\'s corresponding Facade, Handler, Contract and unit testing stubs';

    /**
     * The name, path and options variables placeholder.
     *
     * @var string
     */
    protected $placeholder;

    /**
     * The command directory tree with corresponding template.
     *
     * @var array
     */
    protected $tree = [ 
        'code' => [ 
            'resource-contract' => 'src/{AppPath}/Contracts/{Resources}/{Resource}.php',
            'resource-facade' => 'src/{NamespacePath}/{Resource}.php',
            'resource-handler' => 'src/{NamespacePath}/{Resource}Handler.php',
            'resource-service-provider' => 'src/{NamespacePath}/{Resource}ServiceProvider.php',/**/
        ],
        'views' => [
            'resource-blade' => 'src/views/{resource}.blade.php'
        ],
        'tests' => [
            'resource-stub-handler' => 'tests/Stubs/Stub{Resource}Handler.php'
        ]
    ];/**/

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Populate the name, options and path variables.
     *
     * @return void
     */
    protected function populateVars()
    {
        $this->placeholder['App'] = str_replace('/', '\\', $this->argument('app'));
        $this->placeholder['AppPath'] = str_replace('\\', '/', $this->placeholder['App']);
        $this->placeholder['Resource'] = $this->argument('resourceName');
        $this->placeholder['resource'] = str_replace('_', '-', strtolower(snake_case($this->placeholder['Resource'])));
        $this->placeholder['Resources'] = ($this->option('rn-plural')) ? $this->option('rn-plural') : str_plural($this->argument('resourceName'));
        $this->placeholder['resources'] = str_replace('_', '-', strtolower(snake_case($this->placeholder['Resources'])));
        $this->placeholder['NamespacePath'] = ($this->option('namespace')) ? $this->option('namespace') : $this->placeholder['AppPath'] . '/' . $this->placeholder['Resources'];
        $this->placeholder['Namespace'] = str_replace('/', '\\', $this->placeholder['NamespacePath']);

        $this->placeholder['isViewableResource'] = ($this->option('is-viewable-resource')) ? true : false;

        $this->placeholder['tests'] = ($this->option('no-tests')) ? false : true;

        $this->placeholder['views'] = ($this->option('no-views')) ? false : true;

        if($this->option('contract-ext'))
        {   
            if(preg_match('/\\\|\//', $this->option('contract-ext')))
            {
                $this->placeholder['contractExt'] = str_replace('/', '\\', $this->option('contract-ext'));
                $this->placeholder['contract'] = substr($this->placeholder['contractExt'], strrpos($this->placeholder['contractExt'], '\\') + 1);
            }
            else
            {
                $this->placeholder['contractExt'] = $this->placeholder['App'] . '\\Contracts\\' . $this->option('contract-ext');
                $this->placeholder['contract'] = $this->option('contract-ext');
            }
        }
        if($this->option('handler-imp'))
        {
            if(preg_match('/\\\|\//', $this->option('handler-imp')))
            {
                $this->placeholder['handlerImp'] = str_replace('/', '\\', $this->option('handler-imp'));
                $this->placeholder['handler'] = substr($this->placeholder['handlerImp'], strrpos($this->placeholder['handlerImp'], '\\') + 1);
            }
            else
            {
                $this->placeholder['handlerImp'] = $this->placeholder['App'] . '\\Contracts\\' . $this->option('handler-imp');
                $this->placeholder['handler'] = $this->option('handler-imp');
            }
        }
    }

    /**
     * Recursively walk the $tree and resolve its placholders.
     *
     * @param array
     * @return void
     */
    protected function resolveTree($tree = array())
    {
        foreach ($tree as $key => $value)
        {
            $return = '';
            if(is_array($value))
            {
                $tree[$key] =  $this->resolveTree($value);
            }
            else
            {
                $tree[$key] =  $this->resolvePlaceHolders($value);
            }
        }
        return $tree;
    }

    /**
     * Resolve the placeholders in the $tree.
     *
     * @param array
     * @return void
     */
    protected function resolvePlaceHolders($string)
    {
        preg_match_all('/{\p{L}+}/', $string, $matches);
        foreach ($matches[0] as $value)
        {
            $varName = substr($value, 1, strlen($value)-2);
            $string = str_replace($value, $this->placeholder[$varName], $string);
            //$string = str_replace($value, $this->$varName, $string);
        }
        return $string;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->populateVars();
        $this->tree = $this->resolveTree($this->tree);
        
        if(! $this->generateCodeFiles())
        {
            return false;
        }/**/
        if($this->placeholder['views'])
        {
            if(! $this->generateViewsFiles())
            {
                return false;
            }
        }/**/
        if($this->placeholder['tests'])
        {
            if(! $this->generateTestsFiles())
            {
                return false;
            }
            
        }/**/
        return true;

        //
    }

    protected function generateCodeFiles()
    {
        $app = app();

        $this->info('Generating source code files.');
        foreach ($this->tree['code'] as $key => $value)
        {
            $output = "<?php " . $app['view']->make('boilerplate::generators.' . $key)->with($this->placeholder)->render();
            $file = $app->basePath() . '/' . $value;
            $this->fixPath($file);
            $file_option = (file_exists( $file )) ? 'w' : 'x';
            $fs = fopen($file, $file_option);
            if ($fs) {
                fwrite($fs, $output);
                fclose($fs);
            } else {
                return false;
            }
        }
        return true;
    }

    protected function fixPath($file)
    {
        $path = substr($file, 0, strrpos($file, '/'));
        if(! file_exists($path))
        {
            mkdir($path, 0777, true);
        }
    }

    protected function generateViewsFiles()
    {
        $app = app();

        $this->info('Generating views files.');
        foreach ($this->tree['views'] as $key => $value)
        {
            $fileTemplate = $app['view']->getFinder()->find('boilerplate::generators.' . $key);
            if(file_exists( $fileTemplate ))
            {
                $fileTemplate = file_get_contents($fileTemplate);
            }
            $output = $this->replacePlaceholders($fileTemplate);
            $file = $app->basePath() . '/' . $value;
            $this->fixPath($file);
            $file_option = (file_exists( $file )) ? 'w' : 'x';
            $fs = fopen($file, $file_option);
            if ($fs) {
                fwrite($fs, $output);
                fclose($fs);
            } else {
                return false;
            }/**/
        }
        return true;
    }

    protected function generateTestsFiles()
    {
        $app = app();

        $this->info('Generating unittesting files.');
        foreach ($this->tree['tests'] as $key => $value)
        {
            $fileTemplate = $app['view']->getFinder()->find('boilerplate::generators.' . $key);
            if(file_exists( $fileTemplate ))
            {
                $fileTemplate = file_get_contents($fileTemplate);
            }
            $output = $this->replacePlaceholders($fileTemplate);
            $file = $app->basePath() . '/' . $value;
            $this->fixPath($file);
            $file_option = (file_exists( $file )) ? 'w' : 'x';
            $fs = fopen($file, $file_option);
            if ($fs) {
                fwrite($fs, $output);
                fclose($fs);
            } else {
                return false;
            }
        }/**/
        return true;
    }

    protected function replacePlaceholders($string)
    {
        foreach ($this->placeholder as $key => $value)
        {
            $string = str_replace('{{ $' . $key . ' }}', $value, $string);
        }
        return $string;
    }
}
