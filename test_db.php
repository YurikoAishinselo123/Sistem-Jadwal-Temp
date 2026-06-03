<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$schedules = App\Models\Schedule::with(['dosens', 'laborans'])->get();
foreach ($schedules as $s) {
    echo "ID: {$s->id} | Day: {$s->day} | Time: {$s->start_time} - {$s->end_time} | Makul: {$s->makul_id} | Dosens: ";
    echo implode(',', $s->dosens->pluck('id')->toArray());
    echo " | Laborans: ";
    echo implode(',', $s->laborans->pluck('id')->toArray());
    echo "\n";
}
