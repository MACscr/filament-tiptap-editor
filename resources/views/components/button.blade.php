@props([
    'action' => null,
    'active' => null,
    'label' => null,
    'icon' => null,
])
<button type="button"
    x-on:click="{{ $action }}"
    x-tooltip.raw="{{ $label }}"
    {{ $attributes }}
    class="rounded block p-1 hover:bg-gray-200 focus:bg-gray-200 dark:hover:bg-gray-800 dark:focus:bg-gray-800"
    @if ($active)
    x-bind:class="{ 'bg-gray-300 text-gray-800 dark:bg-gray-600 dark:text-gray-300': isActive({{ $active }}, updatedAt) }"
    @endif
>
    {{ $slot }}
    <x-filament-tiptap-editor::icon icon="{{ $icon }}" />
</button>
