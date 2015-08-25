<?php namespace {{ $App }}\{{ $Resources }}ServiceProvider\Tests\Stubs;

use {{ $App }}\Contracts\{{ $Resources }}\{{ $Resource }};

/**
 * Stub{{ $Resource }}Handler
 *
 */ 

class Stub{{ $Resource }}Handler implements {{ $Resource }} {

    /**
    * The current resource Model name.
    *
    * @var string
    */
    protected $modelName;

    /**
    * The current resource Model full name
    * aka namespaced name
    *
    * @var string
    */
    protected $modelFullName;

    public function __construct()
    {
        $this->setModelName('StubModel', 'Stub\Namespace');
    }

    /**
    * Return a listing of the resource.
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function index()
    {
        return $this;
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param array $data
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function store($data)
    {
        return $this;
    }

    /**
    * Return the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function show($id)
    {
        return $this;
    }

    /**
    * Update the specified resource in storage.
    *
    * @param int $id
    * @param array $data
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function update($id, $data)
    {
        return $this;
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    * @return int (can be checked as boolean)
    */
    public function destroy($id)
    {
        return $this;
    }

    /**
    * Validate data for resource.
    *
    * @param array $data
    * @param array $rules
    * @return boolean
    */
    protected function validate($data, $rules=false)
    {
        return $this;
    }

    /**
    * Set $modelName and $modelFullName.
    *
    * @param string $modelName
    * @param string $namespace
    * @return void
    */
    protected function setModelName($modelName, $namespace)
    {
        $this->modelName = $modelName;
        $this->modelFullName = $namespace . "\\$modelName";
    }
    
    /**
    * Get Resource name from $modelName.
    *
    * @param string $modelName
    * @return $resourceName
    */
    protected function getResourceName()
    {
        // $modelName should end with 'Model'
        // if so, we trim it
        $end = strpos($this->modelName, 'Model');
        $length = strlen($this->modelName);
        $resourceName = ($end == $length - 5) ? substr($this->modelName, 0, $length - 5): $this->modelName;

        // Converting from camelCase to lowercase words
        // camelCase to Words from "ridgerunner" from StackOverflow (user 433790)
        $re = '/(?#! splitCamelCase Rev:20140412)
            # Split camelCase "words". Two global alternatives. Either g1of2:
              (?<=[a-z])      # Position is after a lowercase,
              (?=[A-Z])       # and before an uppercase letter.
            | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
              (?=[A-Z][a-z])  # and before upper-then-lower case.
            /x';
        $resourceName = strtolower(join(' ', preg_split($re, $resourceName)));

        return $resourceName;
    }
}