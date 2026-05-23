import React from 'react';

export default function Button({ type = 'submit', className = '', variant = 'primary', size = 'md', children, disabled, ...props }) {
    const baseStyles = 'inline-flex items-center justify-center font-semibold tracking-widest transition ease-in-out duration-150 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    const variants = {
        primary: 'bg-primary text-ink hover:bg-primary-active focus:bg-primary-active active:bg-primary-active focus:ring-primary',
        dark: 'bg-canvas-dark text-canvas hover:bg-surface-elevated focus:ring-canvas-dark',
        soft: 'bg-canvas-soft text-ink hover:bg-gray-200 focus:ring-gray-300',
        outlineLight: 'bg-canvas text-ink border border-gray-300 hover:bg-gray-50 focus:ring-primary',
        outlineDark: 'bg-canvas-dark text-canvas border border-canvas hover:bg-surface-elevated focus:ring-canvas',
        danger: 'bg-negative text-white hover:bg-red-700 focus:ring-red-500',
    };

    const sizes = {
        sm: 'px-4 py-2 text-sm h-9',
        md: 'px-7 py-3.5 text-base h-12',
        lg: 'px-8 py-4 text-lg h-14',
    };

    return (
        <button
            {...props}
            type={type}
            className={`${baseStyles} ${variants[variant]} ${sizes[size]} ${disabled ? 'opacity-25 cursor-not-allowed' : ''} ${className}`}
            disabled={disabled}
        >
            {children}
        </button>
    );
}
