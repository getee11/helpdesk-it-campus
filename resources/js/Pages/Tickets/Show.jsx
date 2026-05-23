import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import Card from '@/Components/Card';
import Button from '@/Components/Button';
import Badge from '@/Components/Badge';
import TextInput from '@/Components/TextInput';

export default function Show({ ticket, availableTechnicians }) {
    const { auth } = usePage().props;
    const user = auth.user;

    const isTeknisi = user.role === 'teknisi';
    const isAdmin = user.role === 'admin' || user.role === 'superadmin';
    const isAssigned = ticket.technicians.some(t => t.id === user.id);

    const { data: commentData, setData: setCommentData, post: postComment, reset: resetComment, processing: commentProcessing } = useForm({
        content: '',
    });

    const { post: postAction, processing: actionProcessing } = useForm();
    const { data: assignData, setData: setAssignData, post: postAssign, processing: assignProcessing, errors: assignErrors } = useForm({
        technician_id: '',
    });

    const submitAssign = (e) => {
        e.preventDefault();
        postAssign(route('tickets.assign', ticket.id));
    };

    const submitComment = (e) => {
        e.preventDefault();
        postComment(route('tickets.comment', ticket.id), {
            onSuccess: () => resetComment('content'),
        });
    };

    const handleAction = (actionRoute) => {
        postAction(route(actionRoute, ticket.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Tiket ${ticket.ticket_number}`} />

            {/* Dark Band Header */}
            <div className="bg-canvas-dark text-canvas py-12 px-4 sm:px-6 lg:px-8">
                <div className="max-w-[1000px] mx-auto">
                    <Link href={route('tickets.index')} className="text-primary hover:text-primary-active mb-6 inline-block font-semibold">
                        &larr; Kembali ke Daftar
                    </Link>
                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div className="flex items-center gap-3 mb-3">
                                <span className="font-display text-2xl font-bold">{ticket.ticket_number}</span>
                                <Badge type={ticket.priority === 'kritis' ? 'negative' : (ticket.priority === 'tinggi' ? 'warning' : 'neutral')}>
                                    {ticket.priority.toUpperCase()}
                                </Badge>
                                <Badge type={ticket.status === 'resolved' ? 'positive' : (ticket.status === 'progress' ? 'warning' : 'primary')}>
                                    {ticket.status.toUpperCase()}
                                </Badge>
                            </div>
                            <h1 className="font-display text-[40px] font-medium leading-[1.1] tracking-tight">
                                {ticket.subject}
                            </h1>
                        </div>
                        
                        {/* Action Buttons */}
                        <div className="flex gap-3">
                            {ticket.status !== 'resolved' && ticket.status !== 'cancelled' && (
                                <>
                                    {isTeknisi && !isAssigned && (
                                        <Button variant="primary" onClick={() => handleAction('tickets.take')} disabled={actionProcessing}>
                                            Ambil Tugas
                                        </Button>
                                    )}
                                    {isTeknisi && isAssigned && ticket.status !== 'resolved' && (
                                        <Button variant="positive" className="bg-positive text-white" onClick={() => handleAction('tickets.resolve')} disabled={actionProcessing}>
                                            Tandai Selesai
                                        </Button>
                                    )}
                                    {isAdmin && (
                                        <Link href={route('tickets.edit', ticket.id)}>
                                            <Button variant="outlineDark">Edit Tiket</Button>
                                        </Link>
                                    )}
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Light Canvas Content */}
            <div className="max-w-[1000px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {/* Main Content */}
                    <div className="lg:col-span-2 space-y-8">
                        <Card className="shadow-sm">
                            <h2 className="font-display text-2xl font-medium mb-6">Deskripsi Masalah</h2>
                            <div className="prose max-w-none text-body whitespace-pre-wrap">
                                {ticket.description}
                            </div>
                        </Card>

                        <Card className="shadow-sm bg-canvas-soft border-none">
                            <h2 className="font-display text-2xl font-medium mb-6">Komentar & Update</h2>
                            
                            <div className="space-y-6 mb-8">
                                {ticket.comments && ticket.comments.length > 0 ? ticket.comments.map(comment => (
                                    <div key={comment.id} className="bg-canvas p-4 rounded-xl border border-gray-100">
                                        <div className="flex justify-between mb-2">
                                            <span className="font-bold text-ink">{comment.user?.name} <span className="text-mute text-sm font-normal">({comment.user?.role})</span></span>
                                            <span className="text-mute text-sm">{new Date(comment.created_at).toLocaleString('id-ID')}</span>
                                        </div>
                                        <p className="text-body whitespace-pre-wrap">{comment.content}</p>
                                    </div>
                                )) : (
                                    <p className="text-mute text-center italic py-4">Belum ada komentar.</p>
                                )}
                            </div>

                            <form onSubmit={submitComment}>
                                <textarea
                                    className="w-full rounded-xl bg-canvas border-gray-200 p-4 focus:border-primary focus:ring-primary text-ink min-h-[100px] mb-3"
                                    value={commentData.content}
                                    onChange={e => setCommentData('content', e.target.value)}
                                    placeholder="Tambahkan komentar atau update status..."
                                ></textarea>
                                <div className="flex justify-end">
                                    <Button type="submit" variant="dark" disabled={commentProcessing || !commentData.content.trim()}>
                                        Kirim Komentar
                                    </Button>
                                </div>
                            </form>
                        </Card>
                    </div>

                    {/* Sidebar Sidebar */}
                    <div className="space-y-6">
                        <Card className="shadow-sm">
                            <h3 className="font-display text-xl font-medium mb-4">Informasi Pelapor</h3>
                            <div className="space-y-3 text-sm">
                                <div>
                                    <div className="text-mute mb-1">Nama</div>
                                    <div className="font-medium text-ink">{ticket.user?.name}</div>
                                </div>
                                <div>
                                    <div className="text-mute mb-1">Departemen</div>
                                    <div className="font-medium text-ink">{ticket.user?.department?.name || '-'}</div>
                                </div>
                                <div>
                                    <div className="text-mute mb-1">Lokasi Kejadian</div>
                                    <div className="font-medium text-ink">{ticket.location} {ticket.room ? `(${ticket.room})` : ''}</div>
                                </div>
                            </div>
                        </Card>

                        <Card className="shadow-sm">
                            <h3 className="font-display text-xl font-medium mb-4">Detail Tiket</h3>
                            <div className="space-y-3 text-sm">
                                <div>
                                    <div className="text-mute mb-1">Kategori</div>
                                    <div className="font-medium text-ink">{ticket.category?.name}</div>
                                </div>
                                <div>
                                    <div className="text-mute mb-1">Dibuat Pada</div>
                                    <div className="font-medium text-ink">{new Date(ticket.created_at).toLocaleString('id-ID')}</div>
                                </div>
                                <div>
                                    <div className="text-mute mb-1">Teknisi Bertugas</div>
                                    <div className="font-medium text-ink">
                                        {ticket.technicians && ticket.technicians.length > 0 ? (
                                            <ul className="list-disc list-inside">
                                                {ticket.technicians.map(t => <li key={t.id}>{t.name}</li>)}
                                            </ul>
                                        ) : (
                                            <span className="italic text-mute">Belum ada teknisi</span>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </Card>

                        {/* Admin Quick Assign Form */}
                        {isAdmin && ticket.status !== 'resolved' && ticket.status !== 'cancelled' && availableTechnicians?.length > 0 && (
                            <Card className="shadow-sm bg-blue-50 border-blue-100">
                                <h3 className="font-display text-lg font-medium text-blue-900 mb-3">Tugaskan Teknisi (Admin)</h3>
                                <form onSubmit={submitAssign} className="space-y-3">
                                    <select 
                                        className="w-full rounded-xl bg-canvas border-transparent p-3 focus:border-primary focus:ring-primary text-ink text-sm"
                                        value={assignData.technician_id}
                                        onChange={e => setAssignData('technician_id', e.target.value)}
                                        required
                                    >
                                        <option value="">-- Pilih Teknisi --</option>
                                        {availableTechnicians.map(tech => (
                                            <option key={tech.id} value={tech.id}>{tech.name}</option>
                                        ))}
                                    </select>
                                    {assignErrors?.technician_id && <div className="text-negative text-sm">{assignErrors.technician_id}</div>}
                                    <Button type="submit" variant="primary" className="w-full text-sm py-2" disabled={assignProcessing || !assignData.technician_id}>
                                        Tugaskan
                                    </Button>
                                </form>
                            </Card>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
