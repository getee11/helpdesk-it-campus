import React from 'react';

export default function Badge({ children, type = 'neutral', className = '' }) {
    const types = {
        neutral: 'bg-canvas-soft text-ink',
        primary: 'bg-primary text-ink',
        positive: 'bg-positive text-white',
        warning: 'bg-warning text-ink',
        negative: 'bg-negative text-white',
        outline: 'border border-gray-300 text-ink',
    };

    return (
        <span className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${types[type]} ${className}`}>
            {children}
        </span>
    );
}
