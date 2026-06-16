<?php

namespace App\Services;

use App\Models\Complaint;
use Illuminate\Support\Facades\DB;

class ComplaintService
{
    private array $highPriorityKeywords = [
        'urgent', 'emergency', 'leak', 'broken', 'fire', 'smoke', 'flood',
        'danger', 'unsafe', 'bleeding', 'injury', 'sick', 'pain',
        'electric shock', 'gas', 'theft', 'stolen',
    ];

    private array $mediumPriorityKeywords = [
        'not working', 'noisy', 'dirty', 'cold', 'hot', 'smell',
        'slow', 'rude', 'late', 'wrong', 'mistake',
    ];

    public function generateReference(): string
    {
        $year = now()->year;
        $last = Complaint::where('complaint_reference', 'like', "CMP-{$year}-%")
            ->orderByDesc('id')->first();

        $next = 1;
        if ($last) {
            $parts = explode('-', $last->complaint_reference);
            $next = (int) end($parts) + 1;
        }

        return sprintf('CMP-%d-%05d', $year, $next);
    }

    public function detectPriority(string $title, string $description): string
    {
        $text = strtolower($title.' '.$description);

        foreach ($this->highPriorityKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return 'high';
            }
        }

        foreach ($this->mediumPriorityKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return 'medium';
            }
        }

        return 'low';
    }

    public function createComplaint(array $data): Complaint
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['priority'])) {
                $data['priority'] = $this->detectPriority(
                    $data['title'] ?? '',
                    $data['description'] ?? ''
                );
            }

            $complaint = Complaint::create(array_merge($data, [
                'complaint_reference' => $this->generateReference(),
                'submitted_by' => auth()->id(),
                'status' => 'pending',
            ]));

            AuditLogger::log(
                'complaint.created',
                $complaint,
                "Complaint {$complaint->complaint_reference} submitted (priority: {$complaint->priority})"
            );

            return $complaint;
        });
    }

    public function assign(Complaint $complaint, int $userId): Complaint
    {
        return DB::transaction(function () use ($complaint, $userId) {
            $complaint->update([
                'assigned_to' => $userId,
                'assigned_at' => now(),
            ]);

            AuditLogger::log(
                'complaint.assigned',
                $complaint,
                "Complaint {$complaint->complaint_reference} assigned to user #{$userId}"
            );

            return $complaint->fresh();
        });
    }

    public function startWork(Complaint $complaint): Complaint
    {
        return DB::transaction(function () use ($complaint) {
            $complaint->update(['status' => 'in_progress']);

            AuditLogger::log(
                'complaint.started',
                $complaint,
                "Work started on complaint {$complaint->complaint_reference}"
            );

            return $complaint->fresh();
        });
    }

    public function resolve(Complaint $complaint, string $resolutionNotes): Complaint
    {
        return DB::transaction(function () use ($complaint, $resolutionNotes) {
            $complaint->update([
                'status' => 'resolved',
                'resolution_notes' => $resolutionNotes,
                'resolved_at' => now(),
                'resolved_by' => auth()->id(),
            ]);

            AuditLogger::log(
                'complaint.resolved',
                $complaint,
                "Complaint {$complaint->complaint_reference} resolved"
            );

            return $complaint->fresh();
        });
    }

    public function reopen(Complaint $complaint, ?string $reason = null): Complaint
    {
        return DB::transaction(function () use ($complaint, $reason) {
            $complaint->update([
                'status' => 'reopened',
                'resolved_at' => null,
                'resolved_by' => null,
            ]);

            AuditLogger::log(
                'complaint.reopened',
                $complaint,
                'Complaint '.$complaint->complaint_reference.' reopened. Reason: '.($reason ?? 'not provided')
            );

            return $complaint->fresh();
        });
    }
}
