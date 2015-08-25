namespace {{ $Namespace }};

use Illuminate\Support\Facades\Facade;

/**
 * @see \{{ $Namespace }}\{{ $Resource }}Handler
 */
class {{ $Resource }} extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return '{{ $App }}\Contracts\{{ $Resources }}\{{ $Resource }}';
    }
}