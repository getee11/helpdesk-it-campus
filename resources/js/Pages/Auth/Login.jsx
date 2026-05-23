import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import TextInput from '@/Components/TextInput';
import Button from '@/Components/Button';

export default function Login({ status }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            <div className="mb-8 text-center">
                <h1 className="font-display font-medium text-[40px] leading-tight tracking-tight text-canvas">
                    Log in
                </h1>
                <p className="mt-2 text-mute text-sm">
                    Silakan masuk untuk mengakses Helpdesk IT
                </p>
            </div>

            {status && <div className="mb-4 font-medium text-sm text-positive">{status}</div>}

            <form onSubmit={submit}>
                <div>
                    <label htmlFor="email" className="block text-sm font-medium text-canvas mb-2">
                        Email Address
                    </label>
                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full bg-canvas-dark border-gray-700 text-canvas focus:border-primary focus:ring-primary"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    {errors.email && <p className="mt-2 text-sm text-negative">{errors.email}</p>}
                </div>

                <div className="mt-6">
                    <label htmlFor="password" className="block text-sm font-medium text-canvas mb-2">
                        Password
                    </label>
                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full bg-canvas-dark border-gray-700 text-canvas focus:border-primary focus:ring-primary"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />
                    {errors.password && <p className="mt-2 text-sm text-negative">{errors.password}</p>}
                </div>

                <div className="block mt-6">
                    <label className="flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                            className="rounded border-gray-700 bg-canvas-dark text-primary shadow-sm focus:ring-primary"
                        />
                        <span className="ms-2 text-sm text-mute">Remember me</span>
                    </label>
                </div>

                <div className="flex items-center justify-end mt-8">
                    <Button className="w-full" variant="primary" disabled={processing}>
                        Log in
                    </Button>
                </div>
            </form>
        </GuestLayout>
    );
}
