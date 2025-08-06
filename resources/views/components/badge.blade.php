@props(['priority' => 'alacsony'])

<span @class([
    'text-sm',
    'font-medium',
    'px-3',
    'py-1',
    'rounded-full',
    'bg-rose-300 text-rose-800' => $priority === 'magas',
    'bg-orange-300 text-orange-800' => $priority === 'közepes',
    'bg-slate-300 text-slate-800' => $priority === 'alacsony',
]) title="{{ ucfirst($priority) }} prioritás">
    {{ ucfirst($priority) }}
</span>
