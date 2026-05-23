import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import Button from '@/Components/Button';

export default function Authenticated({ children }) {
    const { auth } = usePage().props;
    const user = auth?.user;

    return (
        <div className="min-h-screen bg-canvas-soft">
            {/* Top Nav (Dark Canvas) */}
            <nav className="bg-canvas-dark border-b border-gray-800">
                <div className="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex items-center">
                            <Link href="/dashboard" className="text-canvas font-display font-medium text-xl tracking-tight">
                                IT Helpdesk
                            </Link>
                            
                            <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <Link 
                                    href="/dashboard" 
                                    className={`inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out ${route().current('dashboard') ? 'border-primary text-canvas' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'}`}
                                >
                                    Dashboard
                                </Link>
                                <Link 
                                    href="/tickets" 
                                    className={`inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out ${route().current('tickets.*') ? 'border-primary text-canvas' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'}`}
                                >
                                    Tickets
                                </Link>
                                {(user?.role === 'admin' || user?.role === 'superadmin') && (
                                    <Link 
                                        href="/admin/users" 
                                        className={`inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out ${route().current('admin.*') ? 'border-primary text-canvas' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300'}`}
                                    >
                                        Admin Panel
                                    </Link>
                                )}
                            </div>
                        </div>

                        <div className="hidden sm:flex sm:items-center sm:ms-6">
                            <div className="flex items-center gap-4 text-sm font-medium text-gray-300">
                                <span>{user?.name} ({user?.role})</span>
                                <Link href={route('logout')} method="post" as="button">
                                    <Button variant="outlineDark" size="sm" type="button">Log Out</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            {/* Page Content */}
            <main>{children}</main>

            {/* Footer */}
            <footer className="bg-canvas-dark py-12 mt-24">
                <div className="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 text-center text-on-dark-mute text-sm text-gray-400">
                    &copy; {new Date().getFullYear()} Helpdesk IT Campus. All rights reserved.
                </div>
            </footer>
        </div>
    );
}
