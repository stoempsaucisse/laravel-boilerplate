namespace {{ $App }}\Contracts\{{ $Resources }};

@if (isset($contractExt))
use {{ $contractExt }};
@endif

/**
* {{ $Resource }} interface.
* 
* Used with the Laravel's Service Container to bind the correct {{ $Resources }} implementation
*/
interface {{ $Resource }}@if (isset($contractExt)) extends {{ $contract }} @endif {}