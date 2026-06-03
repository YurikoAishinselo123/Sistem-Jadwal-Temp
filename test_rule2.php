<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rule = new App\Rules\ScheduleCollisionRule();
$rule->setData([
    'periode_id' => 1,
    'day' => 'Rabu',
    'start_time' => '07:50',
    'end_time' => '12:50',
    'makul_id' => 1,
    'dosens' => [3, 4]
]);

$failed = false;
$rule->validate('start_time', '07:50', function($msg) use (&$failed) {
    echo "Validation failed: $msg\n";
    $failed = true;
});
if (!$failed) echo "Validation passed.\n";

