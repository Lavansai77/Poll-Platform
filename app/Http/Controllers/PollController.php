<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Services\VoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PollController extends Controller
{
    public function index(): View
    {
        $polls = Poll::query()
            ->where('status', 'active')
            ->with('options')
            ->orderByDesc('created_at')
            ->get();

        return view('polls.index', compact('polls'));
    }

    public function show(Request $request, Poll $poll): View
    {
        $poll->load('options');

        if ($request->ajax()) {
            return view('polls.show', compact('poll'));
        }

        return view('polls.show-page', compact('poll'));
    }

    public function results(Poll $poll): JsonResponse
    {
        $results = $poll->options()
            ->withCount('votes')
            ->get()
            ->map(fn ($option) => [
                'id' => $option->id,
                'label' => $option->label,
                'votes' => $option->votes_count,
            ]);

        return response()->json([
            'poll_id' => $poll->id,
            'results' => $results,
        ]);
    }

    public function vote(Request $request, Poll $poll, VoteService $service): JsonResponse
    {
        $data = $request->validate([
            'option_id' => ['required', 'integer', 'exists:poll_options,id'],
        ]);

        $result = $service->castVote($poll->id, (int) $data['option_id'], $request->ip());

        if (! $result['allowed']) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'message' => 'Vote recorded successfully.',
        ]);
    }

    public function ipList(Poll $poll): JsonResponse
    {
        $entries = $poll->votes()
            ->with('option')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($vote) => [
                'ip_address' => $vote->ip_address,
                'option' => $vote->option->label,
                'voted_at' => $vote->created_at->toDateTimeString(),
            ]);

        $history = $poll->voteHistory()
            ->with('option')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($vote) => [
                'ip_address' => $vote->ip_address,
                'option' => $vote->option->label,
                'voted_at' => $vote->created_at->toDateTimeString(),
                'released_at' => optional($vote->released_at)->toDateTimeString(),
                'status' => $vote->status,
            ]);

        return response()->json([
            'active_votes' => $entries,
            'history' => $history,
        ]);
    }

    public function releaseIp(Request $request, Poll $poll, VoteService $service): JsonResponse
    {
        $data = $request->validate([
            'ip_address' => ['required', 'ip'],
        ]);

        $service->releaseVote($poll->id, $data['ip_address']);

        return response()->json([
            'message' => 'Vote released.',
        ]);
    }
}
