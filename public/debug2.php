<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$users = App\Models\User::all();
foreach ($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Role: {$user->role}, Active: {$user->is_active}\n";
}
echo "\nTickets:\n";
$tickets = App\Models\Ticket::with('technicians')->get();
foreach ($tickets as $ticket) {
    echo "Ticket ID: {$ticket->id}, Status: {$ticket->status}\n";
    foreach ($ticket->technicians as $tech) {
        echo "  - Tech: {$tech->name}\n";
    }
}
