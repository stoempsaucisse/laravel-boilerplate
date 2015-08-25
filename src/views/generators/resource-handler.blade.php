namespace {{ $Namespace }};

use {{ $App }}\Contracts\{{ $Resources }}\{{ $Resource }};
@if (isset($handlerImp))
use {{ $handlerImp }};
@endif

/**
* {{ $Resource }} Handler.
* 
* We use Laravel's Service Container to dynamicaly inject the right {{ $Resource }} implementation
*/
class {{ $Resource }}Handler @if (isset($handlerImp))extends {{ $handler }} @endif {

    /**
     * The {{ $resource }} implementation.
     */
    protected ${{ $resource }};

    /**
     * Create a new instance.
     *
     * We use Laravel's Service Container to dynamicaly inject the right {{ $Resource }} implementation
     * @param  {{ $Resource }}  ${{ $resource }}
     * @return void
     */
    public function __construct({{ $Resource }} ${{ $resource }})
    {
        $this->{{ $resource }} = ${{ $resource }};
    }
    
    /**
    * Return a listing of the resource.
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function index(){
        return $this->{{ $resource }}->index();
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param array $data
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function store($data){
        return $this->{{ $resource }}->store($data);
    }

    /**
    * Return the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function show($id){
        return $this->{{ $resource }}->show($id);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param int $id
    * @param array $data
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function update($id, $data){
        return $this->{{ $resource }}->update($id, $data);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    * @return int (can be checked as boolean)
    */
    public function destroy($id){
        return $this->{{ $resource }}->destroy($id);
    }
@if ( $isViewableResource )
    /**
    * Display a form to create a new {{ $Resource }}.
    *
    * @return Illuminate\View
    */
    public function create(){

    }
    
    /**
    * Display a form to edit an existing {{ $Resource }}.
    *
    * @param int $id
    * @return Illuminate\View
    */
    public function edit($id){

    }
@endif
}