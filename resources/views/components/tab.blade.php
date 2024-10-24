@props(['id', 'label', 'active' => false, 'href' => '#'])

<li class="mr-2" role="presentation">
    <a class="inline-block p-4 border-b-2 rounded-t-lg {{ $active 
        ? 'border-blue-600 text-blue-600' 
        : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}"
        id="{{ $id }}-tab"
        href="{{ $href }}"
        role="tab"
        aria-controls="{{ $id }}"
        aria-selected="{{ $active ? 'true' : 'false' }}">
        {{ $label }}
    </a>
</li>