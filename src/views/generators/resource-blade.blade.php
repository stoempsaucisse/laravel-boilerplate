{{-- This template should be included with an
    @include('{{ $resources }}::{{ $resources }}', ['id' => @int, '{{ $resource }}' => @int, '{{ $resources }}' => @array]) directive
--}}

@if (isset($id))<input id='{{ $resource }}-id' name='form[{{ $resource }}][id]' value='{{$id}}' type='hidden' />@endif
<label for='form[{{ $resource }}][{{ $resource }}]' class=' ' >{{ $Resource }}</label>
<input id='{{ $resource }}-name' name='form[{{ $resource }}][{{ $resource }}]' class=' ' type='text' @if (isset($id))value='{{${{ $resource }}}}' @endif/>