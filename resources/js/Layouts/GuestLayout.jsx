import React from 'react';

export default function GuestLayout({ children }) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-canvas-dark">
            <div className="w-full sm:max-w-md mt-6 px-6 py-8 bg-surface-elevated text-canvas shadow-xl rounded-2xl sm:rounded-[32px]">
                {children}
            </div>
        </div>
    );
}
