import React, { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import Card from '@/Components/Card';
import Button from '@/Components/Button';
import TextInput from '@/Components/TextInput';

export default function Edit({ ticket, categories, technicians }) {
    const [confirmDelete, setConfirmDelete] = useState(false);
    const [deleteProcessing, setDeleteProcessing] = useState(false);

    const { data, setData, put, processing, errors } = useForm({
        category_id: ticket.category_id,
        priority: ticket.priority,
        status: ticket.status,
        subject: ticket.subject,
        description: ticket.description,
        location: ticket.location,
        room: ticket.room || '',
        technician_ids: ticket.technicians ? ticket.technicians.map(t => t.id) : [],
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('tickets.update', ticket.id));
    };

    const handleDelete = () => {
        setDeleteProcessing(true);
        router.delete(route('tickets.destroy', ticket.id), {
            onSuccess: () => {
                setDeleteProcessing(false);
                setConfirmDelete(false);
            },
            onError: () => {
                setDeleteProcessing(false);
                setConfirmDelete(false);
            }
        });
    };

    const handleTechnicianChange = (e) => {
        const value = Array.from(e.target.selectedOptions, option => parseInt(option.value));
        setData('technician_ids', value);
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Edit Tiket ${ticket.ticket_number}`} />

            {/* Dark Band Header */}
            <div className="bg-canvas-dark text-canvas py-12 px-4 sm:px-6 lg:px-8">
                <div className="max-w-[800px] mx-auto">
                    <Link href={route('tickets.show', ticket.id)} className="text-primary hover:text-primary-active mb-6 inline-block font-semibold">
                        &larr; Batal & Kembali ke Tiket
                    </Link>
                    <h1 className="font-display text-[48px] font-medium leading-[1.0] tracking-tight">
                        Edit Tiket.
                    </h1>
                </div>
            </div>

            {/* Light Canvas Content */}
            <div className="max-w-[800px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <Card className="shadow-sm">
                    <form onSubmit={submit} className="space-y-8">
                        <div>
                            <label className="block text-sm font-bold text-ink mb-2">Subjek Masalah</label>
                            <TextInput
                                className="w-full bg-canvas-soft border-transparent"
                                value={data.subject}
                                onChange={e => setData('subject', e.target.value)}
                            />
                            {errors.subject && <div className="text-negative text-sm mt-1">{errors.subject}</div>}
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label className="block text-sm font-bold text-ink mb-2">Kategori</label>
                                <select 
                                    className="w-full rounded-xl bg-canvas-soft border-transparent h-14 px-4 focus:border-primary focus:ring-primary text-ink"
                                    value={data.category_id}
                                    onChange={e => setData('category_id', e.target.value)}
                                >
                                    {categories.map(cat => (
                                        <option key={cat.id} value={cat.id}>{cat.name}</option>
                                    ))}
                                </select>
                                {errors.category_id && <div className="text-negative text-sm mt-1">{errors.category_id}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-ink mb-2">Prioritas</label>
                                <select 
                                    className="w-full rounded-xl bg-canvas-soft border-transparent h-14 px-4 focus:border-primary focus:ring-primary text-ink"
                                    value={data.priority}
                                    onChange={e => setData('priority', e.target.value)}
                                >
                                    <option value="rendah">Rendah</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="tinggi">Tinggi</option>
                                    <option value="kritis">Kritis</option>
                                </select>
                                {errors.priority && <div className="text-negative text-sm mt-1">{errors.priority}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-ink mb-2">Status</label>
                                <select 
                                    className="w-full rounded-xl bg-canvas-soft border-transparent h-14 px-4 focus:border-primary focus:ring-primary text-ink"
                                    value={data.status}
                                    onChange={e => setData('status', e.target.value)}
                                >
                                    <option value="open">Open</option>
                                    <option value="progress">On Progress</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                {errors.status && <div className="text-negative text-sm mt-1">{errors.status}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-ink mb-2">Teknisi (Bisa pilih multiple dengan Ctrl/Cmd)</label>
                                <select 
                                    multiple
                                    className="w-full rounded-xl bg-canvas-soft border-transparent p-4 focus:border-primary focus:ring-primary text-ink min-h-[120px]"
                                    value={data.technician_ids}
                                    onChange={handleTechnicianChange}
                                >
                                    {technicians.map(tech => (
                                        <option key={tech.id} value={tech.id}>{tech.name}</option>
                                    ))}
                                </select>
                                {errors.technician_ids && <div className="text-negative text-sm mt-1">{errors.technician_ids}</div>}
                            </div>
                        </div>

                        <div>
                            <label className="block text-sm font-bold text-ink mb-2">Deskripsi Detail</label>
                            <textarea
                                className="w-full rounded-xl bg-canvas-soft border-transparent p-4 focus:border-primary focus:ring-primary text-ink min-h-[150px]"
                                value={data.description}
                                onChange={e => setData('description', e.target.value)}
                            ></textarea>
                            {errors.description && <div className="text-negative text-sm mt-1">{errors.description}</div>}
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label className="block text-sm font-bold text-ink mb-2">Gedung / Lokasi</label>
                                <TextInput
                                    className="w-full bg-canvas-soft border-transparent"
                                    value={data.location}
                                    onChange={e => setData('location', e.target.value)}
                                />
                                {errors.location && <div className="text-negative text-sm mt-1">{errors.location}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-ink mb-2">Ruangan</label>
                                <TextInput
                                    className="w-full bg-canvas-soft border-transparent"
                                    value={data.room}
                                    onChange={e => setData('room', e.target.value)}
                                />
                                {errors.room && <div className="text-negative text-sm mt-1">{errors.room}</div>}
                            </div>
                        </div>

                        <div className="pt-6 border-t border-gray-200">
                            <div className="flex gap-3">
                                <Button type="submit" variant="dark" size="lg" className="flex-1 md:flex-none" disabled={processing}>
                                    Simpan Perubahan
                                </Button>
                                <Button 
                                    type="button" 
                                    variant="danger" 
                                    size="lg" 
                                    className="flex-1 md:flex-none"
                                    onClick={() => setConfirmDelete(true)}
                                    disabled={processing}
                                >
                                    Hapus Tiket
                                </Button>
                            </div>
                        </div>
                    </form>
                </Card>

                {/* Delete Confirmation Modal */}
                {confirmDelete && (
                    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                        <div className="bg-white rounded-2xl shadow-lg max-w-md w-full p-6">
                            <h3 className="text-lg font-bold text-ink mb-2">Hapus Tiket</h3>
                            <p className="text-gray-600 mb-6">
                                Apakah Anda yakin ingin menghapus tiket <strong>#{ticket.ticket_number}</strong>? Tindakan ini tidak dapat dibatalkan.
                            </p>
                            <div className="flex gap-3 justify-end">
                                <Button 
                                    type="button"
                                    variant="soft"
                                    onClick={() => setConfirmDelete(false)}
                                    disabled={deleteProcessing}
                                >
                                    Batal
                                </Button>
                                <Button 
                                    type="button"
                                    variant="danger"
                                    onClick={handleDelete}
                                    disabled={deleteProcessing}
                                >
                                    {deleteProcessing ? 'Menghapus...' : 'Hapus'}
                                </Button>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
