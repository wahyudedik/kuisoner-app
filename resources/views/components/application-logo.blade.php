<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Background Circle -->
    <circle cx="100" cy="100" r="90" fill="#3b82f6" opacity="0.1" />

    <!-- Heart Icon (Kesiapan Menikah) -->
    <path
        d="M100 165 C100 165, 50 125, 50 85 C50 65, 65 50, 85 50 C95 50, 100 55, 100 55 C100 55, 105 50, 115 50 C135 50, 150 65, 150 85 C150 125, 100 165, 100 165 Z"
        fill="#ef4444" stroke="#dc2626" stroke-width="2" />

    <!-- Checklist/Questionnaire Icon -->
    <g transform="translate(60, 70)">
        <!-- Document/Paper -->
        <rect x="0" y="0" width="80" height="100" rx="4" fill="#ffffff" stroke="#3b82f6"
            stroke-width="2" />

        <!-- Checkmarks (Questions Answered) -->
        <path d="M15 25 L25 35 L45 15" stroke="#10b981" stroke-width="3" fill="none" stroke-linecap="round"
            stroke-linejoin="round" />
        <path d="M15 45 L25 55 L45 35" stroke="#10b981" stroke-width="3" fill="none" stroke-linecap="round"
            stroke-linejoin="round" />
        <path d="M15 65 L25 75 L45 55" stroke="#10b981" stroke-width="3" fill="none" stroke-linecap="round"
            stroke-linejoin="round" />

        <!-- Question Mark (Active Question) -->
        <circle cx="50" cy="85" r="8" fill="#3b82f6" />
        <path d="M50 78 Q50 74, 54 74 Q58 74, 58 78" stroke="#ffffff" stroke-width="2" fill="none"
            stroke-linecap="round" />
        <line x1="50" y1="88" x2="50" y2="92" stroke="#ffffff" stroke-width="2"
            stroke-linecap="round" />
    </g>

    <!-- Ring Icon (Marriage) -->
    <circle cx="140" cy="60" r="15" fill="none" stroke="#f59e0b" stroke-width="3" />
    <circle cx="140" cy="60" r="12" fill="none" stroke="#f59e0b" stroke-width="2" opacity="0.5" />
</svg>
