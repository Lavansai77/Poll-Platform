@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="h3">Active Polls</h1>
        <div class="list-group" id="poll-list">
            @forelse($polls as $poll)
                <a href="{{ route('polls.show', $poll) }}" class="list-group-item list-group-item-action poll-link" data-poll-id="{{ $poll->id }}">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $poll->question }}</h5>
                        <small>{{ $poll->options->count() }} options</small>
                    </div>
                    <p class="mb-1">Status: {{ ucfirst($poll->status) }}</p>
                </a>
            @empty
                <div class="alert alert-info">No active polls yet.</div>
            @endforelse
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5">How to vote</h2>
                <p class="mb-0">Select a poll to load options without reloading the page. Voting and results update live via AJAX.</p>
            </div>
        </div>
    </div>
</div>

<div id="poll-detail" class="mt-4"></div>
@endsection
