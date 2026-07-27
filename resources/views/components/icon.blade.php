@props(['name', 'class' => ''])

@php
    $paths = [
        'arrow-right' => '<line x1="4" y1="12" x2="20" y2="12"/><polyline points="13 5 20 12 13 19"/>',
        'arrow-up-right' => '<line x1="6" y1="18" x2="18" y2="6"/><polyline points="8 6 18 6 18 16"/>',
        'chevron-down' => '<polyline points="5 8 12 15 19 8"/>',
        'chevron-up' => '<polyline points="5 16 12 9 19 16"/>',
        'x' => '<line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/>',
        'plus' => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
        'phone' => '<path d="M4 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 2 6a2 2 0 0 1 2-2z"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="1"/><polyline points="3 6 12 13 21 6"/>',
        'map-pin' => '<path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z"/><circle cx="12" cy="10" r="2.3"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15.5 14"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><line x1="3" y1="12" x2="21" y2="12"/><path d="M12 3c2.5 2.4 4 5.6 4 9s-1.5 6.6-4 9c-2.5-2.4-4-5.6-4-9s1.5-6.6 4-9z"/>',
        'smartphone' => '<rect x="6" y="3" width="12" height="18" rx="2"/><line x1="11" y1="18" x2="13" y2="18"/>',
        'star' => '<polygon points="12 2.5 15 9 22 10 17 15 18.3 21.5 12 18.2 5.7 21.5 7 15 2 10 9 9"/>',
        'check' => '<polyline points="4 12 9 17 20 6"/>',
        'quote' => '<path d="M7 6c-3 1.5-4 4-4 7 0 2.2 1.3 3.5 3 3.5S9 15.2 9 13c0-1.6-1-2.8-2.4-3.2C7 8.5 8 7 10 6.2L9 4C8 4.3 7.5 4.5 7 6zm10 0c-3 1.5-4 4-4 7 0 2.2 1.3 3.5 3 3.5s3-1.3 3-3.5c0-1.6-1-2.8-2.4-3.2C17 8.5 18 7 20 6.2L19 4c-1 .3-1.5.5-2 2z"/>',
        'play' => '<circle cx="12" cy="12" r="9"/><polygon points="10 8 16 12 10 16"/>',
        'facebook' => '<path d="M14 21v-7h2.3l.4-3H14V9c0-.9.2-1.5 1.6-1.5H17V5c-.3 0-1.2-.1-2.3-.1-2.3 0-3.7 1.4-3.7 3.9V11H8.5v3H11v7z"/>',
        'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="4.5"/><circle cx="12" cy="12" r="3.8"/><circle cx="17" cy="7" r="0.8" fill="currentColor" stroke="none"/>',
        'linkedin' => '<rect x="3.5" y="3.5" width="17" height="17" rx="2"/><line x1="8" y1="10.5" x2="8" y2="16"/><circle cx="8" cy="7.3" r="0.9" fill="currentColor" stroke="none"/><path d="M11.5 16v-3.2c0-1.4.9-2.3 2.1-2.3s1.9 1 1.9 2.3V16"/>',
        'twitter' => '<path d="M4 4l7.5 9.3L4.4 20H7l5.2-5.6L16.5 20H20l-8-9.9L18.7 4h-2.6l-4.7 5.1L7.5 4z"/>',
        'youtube' => '<rect x="3" y="6" width="18" height="12" rx="3"/><polygon points="10.5 9.5 15.5 12 10.5 14.5" fill="currentColor" stroke="none"/>',
        'whatsapp' => '<path d="M7 17l-1.4 3.5L9 19a8 8 0 1 0-3.6-3.4z"/><path d="M9 8.5c0 3.5 3 6.5 6.5 6.5.5 0 .8-.4.6-.9l-.6-1.4c-.1-.4-.6-.5-.9-.3l-.8.5a5 5 0 0 1-3.2-3.2l.5-.8c.2-.3.1-.8-.3-.9L9.4 7.4c-.5-.2-.9.1-.9.6z" fill="currentColor" stroke="none"/>',
        'building' => '<rect x="5" y="3" width="9" height="18"/><rect x="14" y="9" width="5" height="12"/><line x1="8" y1="6.5" x2="8" y2="6.5"/><line x1="11" y1="6.5" x2="11" y2="6.5"/>',
        'compass' => '<circle cx="12" cy="12" r="9"/><polygon points="15 9 13 13 9 15 11 11"/>',
        'shield' => '<path d="M12 3l7 3v6c0 4.5-3 7.7-7 9-4-1.3-7-4.5-7-9V6z"/>',
        'layers' => '<polygon points="12 3 21 8 12 13 3 8"/><polyline points="3 13 12 18 21 13"/><polyline points="3 16.5 12 21.5 21 16.5"/>',
        'grid' => '<rect x="3" y="3" width="7.5" height="7.5"/><rect x="13.5" y="3" width="7.5" height="7.5"/><rect x="3" y="13.5" width="7.5" height="7.5"/><rect x="13.5" y="13.5" width="7.5" height="7.5"/>',
        'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1" fill="currentColor" stroke="none"/>',
        'heart-pulse' => '<path d="M20 8.5c0-2.5-2-4.2-4.2-4.2-1.3 0-2.6.7-3.3 1.8-.7-1.1-2-1.8-3.3-1.8C7 4.3 5 6 5 8.5c0 .7.1 1.3.4 1.9H7l1.5-3 2 5 1.5-3H18"/><path d="M5.4 10.4C6.5 13.8 12 18 12 18s5.5-4.2 6.6-7.6"/>',
        'download' => '<path d="M12 4v11"/><polyline points="7 11 12 16 17 11"/><line x1="5" y1="20" x2="19" y2="20"/>',
        'send' => '<line x1="3" y1="12" x2="21" y2="3"/><polygon points="21 3 14.5 21 11 13 3 9.5"/>',
    ];

    $svg = $paths[$name] ?? $paths['arrow-right'];
@endphp

<svg {{ $attributes->class([$class])->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.5', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    {!! $svg !!}
</svg>
