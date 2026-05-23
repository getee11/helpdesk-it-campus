import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import Card from '@/Components/Card';
import Button from '@/Components/Button';
import Badge from '@/Components/Badge';

export default function Dashboard({ auth, stats, recentTickets, statusDistribution, technicians }) {
    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />

            {/* Hero Band Dark */}
            <div className="bg-canvas-dark text-canvas py-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
                <div className="max-w-[1200px] mx-auto relative z-10">
                    <h1 className="font-display text-[80px] sm:text-[100px] lg:text-[136px] font-medium leading-[1.0] tracking-[-0.02em] mb-8">
                        IT Support,<br />Simplified.
                    </h1>
                    <p className="text-xl sm:text-2xl text-gray-400 max-w-2xl mb-12">
                        Pusat bantuan layanan IT Kampus. Sampaikan kendala Anda, dan tim teknisi kami akan segera menyelesaikannya.
                    </p>
                    <div className="flex flex-wrap gap-4">
                        <Link href={route('tickets.create')}>
                            <Button variant="primary" size="lg">Buat Tiket Baru</Button>
                        </Link>
                        <Link href={route('tickets.index')}>
                            <Button variant="outlineDark" size="lg">Lihat Semua Tiket</Button>
                        </Link>
                    </div>
                </div>
            </div>

            {/* Main Content - Light Band */}
            <div className="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
                
                {/* Stats Grid */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16 -mt-32 relative z-20">
                    {stats.map((stat, index) => (
                        <Card key={index} elevated={true} dark={true} className="flex flex-col justify-between h-40">
                            <div className="text-gray-400 text-sm font-medium tracking-wide uppercase">{stat.label}</div>
                            <div className="flex items-end justify-between">
                                <div className="font-display text-5xl font-medium" style={{ color: stat.accent ? '#9fe870' : (stat.color || '#ffffff') }}>
                                    {stat.num}
                                </div>
                                <i className={`bi ${stat.icon} text-3xl opacity-50`}></i>
                            </div>
                        </Card>
                    ))}
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Recent Tickets */}
                    <div className="lg:col-span-2">
                        <div className="flex items-center justify-between mb-8">
                            <h2 className="font-display text-3xl font-medium tracking-tight">Tiket Terbaru</h2>
                        </div>
                        <div className="space-y-4">
                            {recentTickets.length > 0 ? recentTickets.map(ticket => (
                                <Card key={ticket.id} className="transition-all hover:shadow-md">
                                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div>
                                            <div className="flex items-center gap-3 mb-2">
                                                <span className="font-semibold text-lg">{ticket.ticket_number}</span>
                                                <Badge type={
                                                    ticket.priority === 'kritis' ? 'negative' : 
                                                    (ticket.priority === 'tinggi' ? 'warning' : 'neutral')
                                                }>
                                                    {ticket.priority}
                                                </Badge>
                                                <Badge type={
                                                    ticket.status === 'resolved' ? 'positive' : 
                                                    (ticket.status === 'progress' ? 'warning' : 'primary')
                                                }>
                                                    {ticket.status}
                                                </Badge>
                                            </div>
                                            <h3 className="font-medium text-xl mb-1">{ticket.subject}</h3>
                                            <p className="text-mute text-sm">
                                                Dilaporkan oleh {ticket.user.name} • {new Date(ticket.created_at).toLocaleDateString('id-ID')}
                                            </p>
                                        </div>
                                        <div>
                                            <Link href={route('tickets.show', ticket.id)}>
                                                <Button variant="soft">Detail</Button>
                                            </Link>
                                        </div>
                                    </div>
                                </Card>
                            )) : (
                                <Card className="text-center py-12">
                                    <p className="text-mute">Belum ada tiket terbaru.</p>
                                </Card>
                            )}
                        </div>
                    </div>

                    {/* Status Distribution */}
                    <div>
                        <h2 className="font-display text-3xl font-medium tracking-tight mb-8">Status</h2>
                        <Card className="flex flex-col gap-6">
                            <div>
                                <div className="flex justify-between text-sm mb-2">
                                    <span className="font-medium">Open</span>
                                    <span className="text-mute">{statusDistribution.open.count} ({statusDistribution.open.percent}%)</span>
                                </div>
                                <div className="w-full bg-gray-100 rounded-full h-2">
                                    <div className="bg-blue-500 h-2 rounded-full" style={{ width: `${statusDistribution.open.percent}%` }}></div>
                                </div>
                            </div>
                            <div>
                                <div className="flex justify-between text-sm mb-2">
                                    <span className="font-medium">On Progress</span>
                                    <span className="text-mute">{statusDistribution.progress.count} ({statusDistribution.progress.percent}%)</span>
                                </div>
                                <div className="w-full bg-gray-100 rounded-full h-2">
                                    <div className="bg-warning h-2 rounded-full" style={{ width: `${statusDistribution.progress.percent}%` }}></div>
                                </div>
                            </div>
                            <div>
                                <div className="flex justify-between text-sm mb-2">
                                    <span className="font-medium">Resolved</span>
                                    <span className="text-mute">{statusDistribution.resolved.count} ({statusDistribution.resolved.percent}%)</span>
                                </div>
                                <div className="w-full bg-gray-100 rounded-full h-2">
                                    <div className="bg-positive h-2 rounded-full" style={{ width: `${statusDistribution.resolved.percent}%` }}></div>
                                </div>
                            </div>
                        </Card>
                    </div>
                </div>

            </div>
        </AuthenticatedLayout>
    );
}
