<?php

namespace App\Service;

class SlotGenerator
{
    public function generateSlots(\DateTimeInterface $startTime, \DateTimeInterface $endTime, int $slotDurationInMin): array
    {
        $slots = [];
        $currentStartTime = $startTime;

        while ($currentStartTime < $endTime) {
            $currentEndTime = $currentStartTime->modify("+{$slotDurationInMin} minutes");
            if ($currentEndTime > $endTime) {
                break;
            }

            $slots[] = [
                'start_time' => $currentStartTime,
            ];

            $currentStartTime = $currentEndTime;
        }

        return $slots;
    }
}