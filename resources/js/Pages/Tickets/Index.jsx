import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import Card from '@/Components/Card';
import Button from '@/Components/Button';
import Badge from '@/Components/Badge';

export default function Index({ tickets, categories, technicians, counts }) {
    const handleFilter = (status) => {
        router.get(route('tickets.index'), { status }, { preserveState: true });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Daftar Tiket" />

            {/* Dark Band Header */}
            <div className="bg-canvas-dark text-canvas py-16 px-4 sm:px-6 lg:px-8">
                <div className="max-w-[1200px] mx-auto flex flex-col md:flex-row md:items-end justify-between gap-8">
                    <div>
                        <h1 className="font-display text-[64px] font-medium leading-[1.0] tracking-tight mb-4">
                            Tiket Anda.
                        </h1>
                        <p className="text-gray-400 text-xl max-w-lg">
                            Pantau dan kelola laporan masalah IT yang sedang berlangsung.
                        </p>
                    </div>
                    <div>
                        <Link href={route('tickets.create')}>
                            <Button variant="primary" size="lg">Buat Tiket Baru</Button>
                        </Link>
                    </div>
                </div>
            </div>

            {/* Light Canvas Content */}
            <div className="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
                
                {/* Status Tabs */}
                <div className="flex flex-wrap gap-3 mb-12">
                    <button onClick={() => handleFilter('all')} className="bg-canvas-soft text-ink px-6 py-2 rounded-full font-semibold hover:bg-gray-200 transition">
                        Semua ({counts.all})
                    </button>
                    <button onClick={() => handleFilter('open')} className="bg-blue-100 text-blue-800 px-6 py-2 rounded-full font-semibold hover:bg-blue-200 transition">
                        Open ({counts.open})
                    </button>
                    <button onClick={() => handleFilter('progress')} className="bg-yellow-100 text-yellow-800 px-6 py-2 rounded-full font-semibold hover:bg-yellow-200 transition">
                        On Progress ({counts.progress})
                    </button>
                    <button onClick={() => handleFilter('resolved')} className="bg-green-100 text-green-800 px-6 py-2 rounded-full font-semibold hover:bg-green-200 transition">
                        Resolved ({counts.resolved})
                    </button>
                </div>

                {/* Tickets List */}
                <div className="space-y-6">
                    {tickets.data.length > 0 ? tickets.data.map(ticket => (
                        <Card key={ticket.id} className="transition-all hover:shadow-lg group">
                            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div className="flex-1">
                                    <div className="flex flex-wrap items-center gap-3 mb-3">
                                        <span className="font-semibold text-lg">{ticket.ticket_number}</span>
                                        <Badge type={ticket.priority === 'kritis' ? 'negative' : (ticket.priority === 'tinggi' ? 'warning' : 'neutral')}>
                                            {ticket.priority.toUpperCase()}
                                        </Badge>
                                        <Badge type={ticket.status === 'resolved' ? 'positive' : (ticket.status === 'progress' ? 'warning' : 'primary')}>
                                            {ticket.status.toUpperCase()}
                                        </Badge>
                                        <span className="text-mute text-sm">{ticket.category?.name}</span>
                                    </div>
                                    <h3 className="font-medium text-2xl mb-2 group-hover:text-primary transition-colors">
                                        {ticket.subject}
                                    </h3>
                                    <p className="text-mute text-sm">
                                        Pelapor: {ticket.user?.name} • Lokasi: {ticket.location} • Dibuat: {new Date(ticket.created_at).toLocaleDateString('id-ID')}
                                    </p>
                                </div>
                                <div className="shrink-0">
                                    <Link href={route('tickets.show', ticket.id)}>
                                        <Button variant="dark" className="w-full md:w-auto">Lihat Detail</Button>
                                    </Link>
                                </div>
                            </div>
                        </Card>
                    )) : (
                        <div className="text-center py-24 bg-canvas-soft rounded-3xl">
                            <h3 className="text-2xl font-medium text-ink mb-2">Tidak ada tiket ditemukan</h3>
                            <p className="text-mute">Coba ubah filter atau buat tiket baru.</p>
                        </div>
                    )}
                </div>

                {/* Pagination */}
                {tickets.links && tickets.links.length > 3 && (
                    <div className="mt-12 flex justify-center gap-2">
                        {tickets.links.map((link, k) => (
                            <Link 
                                key={k} 
                                href={link.url || '#'}
                                className={`px-4 py-2 rounded-lg font-medium transition ${link.active ? 'bg-canvas-dark text-canvas' : 'bg-canvas-soft text-ink hover:bg-gray-200'} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ))}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
