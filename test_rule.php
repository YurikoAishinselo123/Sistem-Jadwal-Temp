<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dosens = [3, 4];
$query = App\Models\Schedule::where('periode_id', 1)
            ->where('day', 'Rabu')
            ->where(function ($q) {
                $q->whereTime('start_time', '<', '12:50')
                  ->whereTime('end_time', '>', '07:50');
            })
            ->where(function ($q) use ($dosens) {
                $q->where('makul_id', 1);
                $q->orWhereHas('dosens', function ($subQ) use ($dosens) {
                    $subQ->whereIn('dosens.id', $dosens);
                });
            });

echo $query->toSql();
echo "\n";
print_r($query->getBindings());

