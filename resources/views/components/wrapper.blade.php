@props(['maxWidth' => '4xl'])

<div class="max-w-{{ $maxWidth }} mx-auto sm:px-6 lg:px-8 py-6">
    {{ $slot }}
</div>