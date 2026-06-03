<?php

namespace App\Rules;

use App\Models\Schedule;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class ScheduleCollisionRule implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    protected $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $day = $this->data['day'] ?? null;
        $startTime = $this->data['start_time'] ?? null;
        $endTime = $this->data['end_time'] ?? null;
        $makulId = $this->data['makul_id'] ?? null;
        $prodiId = $this->data['prodi_id'] ?? null;
        $class = $this->data['class'] ?? null;
        $dosens = $this->data['dosens'] ?? [];
        $laborans = $this->data['laborans'] ?? [];
        $periodeId = $this->data['periode_id'] ?? null;

        if (!$day || !$startTime || !$endTime || !$makulId || !$periodeId) {
            return;
        }

        $query = Schedule::where('periode_id', $periodeId)
            ->where('day', $day)
            ->where(function ($q) use ($startTime, $endTime) {
                // Formatting into H:i:s to ensure consistent comparison in DB
                $start = strlen($startTime) == 5 ? $startTime . ':00' : $startTime;
                $end = strlen($endTime) == 5 ? $endTime . ':00' : $endTime;

                $q->whereTime('start_time', '<', $end)
                    ->whereTime('end_time', '>', $start);
            })
            ->where(function ($q) use ($makulId, $dosens, $laborans, $prodiId, $class) {
                $q->where('makul_id', $makulId);

                if ($prodiId && $class) {
                    $q->orWhere(function ($subQ) use ($prodiId, $class) {
                        $subQ->where('prodi_id', $prodiId)
                            ->where('class', $class);
                    });
                }

                if (!empty($dosens)) {
                    $q->orWhereHas('dosens', function ($subQ) use ($dosens) {
                        $subQ->whereIn('dosens.id', $dosens);
                    });
                }

                if (!empty($laborans)) {
                    $q->orWhereHas('laborans', function ($subQ) use ($laborans) {
                        $subQ->whereIn('laborans.id', $laborans);
                    });
                }
            });

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        $conflictingSchedules = $query->with(['dosens', 'laborans'])->get();

        if ($conflictingSchedules->isNotEmpty()) {
            $hasMakulError = false;
            $hasDosenError = false;
            $hasLaboranError = false;
            $hasKelasError = false;

            foreach ($conflictingSchedules as $schedule) {
                if (!$hasMakulError && $schedule->makul_id == $makulId) {
                    $fail('Mata kuliah ini sudah dijadwalkan pada hari dan waktu yang sama.');
                    $hasMakulError = true;
                }

                if (!$hasKelasError && $prodiId && $class && $schedule->prodi_id == $prodiId && $schedule->class === $class) {
                    $fail("Kelas {$class} dari Program Studi ini sudah memiliki jadwal pada hari dan waktu tersebut.");
                    $hasKelasError = true;
                }

                if (!$hasDosenError && !empty($dosens)) {
                    $conflictDosens = $schedule->dosens->pluck('id')->intersect($dosens);
                    if ($conflictDosens->isNotEmpty()) {
                        $fail('Dosen yang dipilih sudah memiliki jadwal mengajar di hari dan waktu tersebut.');
                        $hasDosenError = true;
                    }
                }

                if (!$hasLaboranError && !empty($laborans)) {
                    $conflictLaborans = $schedule->laborans->pluck('id')->intersect($laborans);
                    if ($conflictLaborans->isNotEmpty()) {
                        $fail('Laboran yang dipilih sudah bertugas di hari dan waktu tersebut.');
                        $hasLaboranError = true;
                    }
                }
            }

            // if (!$hasMakulError && !$hasKelasError && !$hasDosenError && !$hasLaboranError) {
            //     $fail('Terdapat jadwal yang bertabrakan pada hari dan waktu tersebut.');
            // }
        }
    }
}
