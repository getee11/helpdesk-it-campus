<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    $ticket = App\Models\Ticket::first();
    $user = App\Models\User::where('role', 'teknisi')->first();
    if(!$ticket || !$user) {
        echo 'No data';
    } else {
        $ticket->technicians()->syncWithoutDetaching([$user->id]);
        echo 'Success';
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
