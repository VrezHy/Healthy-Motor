@props([
'variant' => 'primary',
'href' => null,
'type' => 'button',
'rounded' => true
])

@php
$baseVariant = 'inline-flex text-white items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 disabled:opacity-50 disabled:pointer-events-none';


$variants = [
'primary' => 'bg-indigo-400 text-white hover:bg-indigo-700',
'outline' => 'bg-white text-indigo-600 border-indigo-600 hover:bg-indigo-50',
'ghost' => 'bg-transparent text-indigo-600 hover:bg-indigo-50 hover:text-indigo-800',
'dark' => 'bg-indigo-900 text-white border-indigo-900 hover:bg-indigo-950',
'white' => 'bg-white text-indigo-600 border-indigo-200 hover:bg-indigo-50 hover:border-indigo-300',
];

$variantClass = $variants[$variant] ?? $variants['primary'];


$roundingClass = $rounded ? 'rounded-full px-6 py-2' : 'rounded-none w-full py-4';


$classes = "{$baseVariant} {$roundingClass} {$variantClass}";
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
@endif