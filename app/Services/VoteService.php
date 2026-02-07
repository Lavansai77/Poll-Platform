<?php

namespace App\Services;

use App\Models\Vote;
use App\Models\VoteHistory;
use Carbon\CarbonImmutable;

class VoteService
{
    public function castVote(int $pollId, int $optionId, string $ipAddress): array
    {
        $existing = Vote::query()
            ->where('poll_id', $pollId)
            ->where('ip_address', $ipAddress)
            ->first();

        if ($existing) {
            return [
                'allowed' => false,
                'message' => 'This IP has already voted for this poll.',
            ];
        }

        $vote = Vote::query()->create([
            'poll_id' => $pollId,
            'option_id' => $optionId,
            'ip_address' => $ipAddress,
        ]);

        VoteHistory::query()->create([
            'poll_id' => $pollId,
            'option_id' => $optionId,
            'ip_address' => $ipAddress,
            'status' => 'cast',
            'released_at' => null,
            'created_at' => $vote->created_at ?? CarbonImmutable::now(),
            'updated_at' => $vote->updated_at ?? CarbonImmutable::now(),
        ]);

        return [
            'allowed' => true,
            'message' => 'Vote recorded.',
        ];
    }

    public function releaseVote(int $pollId, string $ipAddress): void
    {
        $vote = Vote::query()
            ->where('poll_id', $pollId)
            ->where('ip_address', $ipAddress)
            ->first();

        if (! $vote) {
            return;
        }

        VoteHistory::query()->where('poll_id', $pollId)
            ->where('ip_address', $ipAddress)
            ->where('status', 'cast')
            ->orderByDesc('created_at')
            ->limit(1)
            ->update([
                'status' => 'released',
                'released_at' => CarbonImmutable::now(),
            ]);

        $vote->delete();
    }
}
