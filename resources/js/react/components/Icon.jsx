import React from 'react';

const icons = {
    compass: (
        <>
            <circle cx="12" cy="12" r="8.5" />
            <path d="M14.9 9.1 13 13l-3.9 1.9L11 11z" />
        </>
    ),
    briefcase: (
        <>
            <path d="M8 7V5.8A1.8 1.8 0 0 1 9.8 4h4.4A1.8 1.8 0 0 1 16 5.8V7" />
            <rect x="4" y="7" width="16" height="11" rx="2.5" />
            <path d="M4 11.5h16" />
        </>
    ),
    newspaper: (
        <>
            <path d="M6 5.5h10.5A1.5 1.5 0 0 1 18 7v10a1.5 1.5 0 0 1-1.5 1.5H7.5A2.5 2.5 0 0 1 5 16V7a1.5 1.5 0 0 1 1-1.5Z" />
            <path d="M8.5 9.5h6" />
            <path d="M8.5 12.5h6" />
            <path d="M8.5 15.5h3.5" />
        </>
    ),
    building: (
        <>
            <path d="M6 19V6.5A1.5 1.5 0 0 1 7.5 5h6A1.5 1.5 0 0 1 15 6.5V19" />
            <path d="M4 19h16" />
            <path d="M9 8.5h.01M12 8.5h.01M9 11.5h.01M12 11.5h.01M9 14.5h.01M12 14.5h.01" />
        </>
    ),
    shield: (
        <>
            <path d="M12 4.5 18 7v4.8c0 3.6-2.3 6.9-6 8.2-3.7-1.3-6-4.6-6-8.2V7z" />
            <path d="m9.5 12 1.7 1.7 3.3-3.4" />
        </>
    ),
    chart: (
        <>
            <path d="M5 19.5h14" />
            <path d="M7.5 16V11" />
            <path d="M12 16V8" />
            <path d="M16.5 16V6" />
        </>
    ),
    user: (
        <>
            <circle cx="12" cy="8.5" r="3.2" />
            <path d="M6.5 19a5.5 5.5 0 0 1 11 0" />
        </>
    ),
    spark: (
        <>
            <path d="m12 4 1.8 4.2L18 10l-4.2 1.8L12 16l-1.8-4.2L6 10l4.2-1.8z" />
        </>
    ),
    arrow: (
        <>
            <path d="M5 12h14" />
            <path d="m13 7 5 5-5 5" />
        </>
    ),
};

export default function Icon({ name, className = '', size = 18 }) {
    const icon = icons[name];

    if (!icon) {
        return null;
    }

    return (
        <svg
            className={className}
            width={size}
            height={size}
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.8"
            strokeLinecap="round"
            strokeLinejoin="round"
            aria-hidden="true"
        >
            {icon}
        </svg>
    );
}
