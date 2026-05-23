import React from 'react';

export default function Card({ children, className = '', elevated = false, dark = false }) {
    const bgClass = dark 
        ? (elevated ? 'bg-surface-elevated text-canvas' : 'bg-canvas-dark text-canvas') 
        : (elevated ? 'bg-canvas-soft text-ink' : 'bg-canvas text-ink border border-gray-100');
        
    return (
        <div className={`rounded-[24px] p-8 ${bgClass} ${className}`}>
            {children}
        </div>
    );
}
