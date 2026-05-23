import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import Card from '@/Components/Card';
import Button from '@/Components/Button';
import TextInput from '@/Components/TextInput';

export default function Create({ categories }) {
    const { data, setData, post, processing, errors } = useForm({
        category_id: '',
        priority: 'sedang',
        subject: '',
        description: '',
        location: '',
        room: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('tickets.store'));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Buat Tiket Baru" />

            {/* Dark Band Header */}
            <div className="bg-canvas-dark text-canvas py-12 px-4 sm:px-6 lg:px-8">
                <div className="max-w-[800px] mx-auto">
                    <Link href={route('tickets.index')} className="text-primary hover:text-primary-active mb-6 inline-block font-semibold">
                        &larr; Kembali ke Daftar
                    </Link>
                    <h1 className="font-display text-[48px] font-medium leading-[1.0] tracking-tight">
                        Ajukan Tiket.
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
                                placeholder="Contoh: Proyektor mati di kelas A"
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
                                    <option value="">-- Pilih Kategori --</option>
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
                        </div>

                        <div>
                            <label className="block text-sm font-bold text-ink mb-2">Deskripsi Detail</label>
                            <textarea
                                className="w-full rounded-xl bg-canvas-soft border-transparent p-4 focus:border-primary focus:ring-primary text-ink min-h-[150px]"
                                value={data.description}
                                onChange={e => setData('description', e.target.value)}
                                placeholder="Jelaskan masalah secara mendetail..."
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
                                    placeholder="Contoh: Gedung Rektorat"
                                />
                                {errors.location && <div className="text-negative text-sm mt-1">{errors.location}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-ink mb-2">Ruangan (Opsional)</label>
                                <TextInput
                                    className="w-full bg-canvas-soft border-transparent"
                                    value={data.room}
                                    onChange={e => setData('room', e.target.value)}
                                    placeholder="Contoh: Ruang Rapat Lt. 2"
                                />
                                {errors.room && <div className="text-negative text-sm mt-1">{errors.room}</div>}
                            </div>
                        </div>

                        <div className="pt-6 border-t border-gray-200">
                            <Button type="submit" variant="dark" size="lg" className="w-full md:w-auto" disabled={processing}>
                                Kirim Laporan Tiket
                            </Button>
                        </div>
                    </form>
                </Card>
            </div>
        </AuthenticatedLayout>
    );
}
