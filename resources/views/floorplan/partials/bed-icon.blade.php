@switch($layout)
    @case('single')
        <svg viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="6" width="30" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
            <rect x="5" y="9" width="10" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/>
            <line x1="2" y1="20" x2="2" y2="23" stroke="currentColor" stroke-width="1.5"/>
            <line x1="32" y1="20" x2="32" y2="23" stroke="currentColor" stroke-width="1.5"/>
        </svg>
        @break
    @case('double')
        <svg viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="6" width="36" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
            <rect x="5" y="9" width="14" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/>
            <rect x="21" y="9" width="14" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/>
            <line x1="2" y1="20" x2="2" y2="23" stroke="currentColor" stroke-width="1.5"/>
            <line x1="38" y1="20" x2="38" y2="23" stroke="currentColor" stroke-width="1.5"/>
        </svg>
        @break
    @case('twin')
        <svg viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="6" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
            <rect x="5" y="9" width="10" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/>
            <rect x="22" y="6" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
            <rect x="25" y="9" width="10" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/>
        </svg>
        @break
    @case('suite')
        <svg viewBox="0 0 48 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="4" width="26" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
            <rect x="5" y="7" width="9" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/>
            <rect x="16" y="7" width="9" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/>
            <rect x="32" y="12" width="14" height="8" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
            <circle cx="36" cy="16" r="1" fill="currentColor"/>
            <circle cx="42" cy="16" r="1" fill="currentColor"/>
        </svg>
        @break
    @default
        <svg viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="6" width="36" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
        </svg>
@endswitch
