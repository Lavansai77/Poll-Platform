<div class="card shadow-sm" data-poll-id="{{ $poll->id }}" id="poll-card">
    <div class="card-body">
        <h2 class="h4">{{ $poll->question }}</h2>
        <form id="vote-form">
            @csrf
            <div class="list-group mb-3">
                @foreach($poll->options as $option)
                    <label class="list-group-item d-flex align-items-center">
                        <input class="form-check-input me-2" type="radio" name="option_id" value="{{ $option->id }}" required>
                        <span>{{ $option->label }}</span>
                    </label>
                @endforeach
            </div>
            <button class="btn btn-primary" type="submit">Submit Vote</button>
            <div class="mt-2" id="vote-message"></div>
        </form>

        <hr>

        <h3 class="h5">Live Results</h3>
        <div id="poll-results" class="mb-3"></div>

        <div class="border rounded p-3 bg-light">
            <h4 class="h6">Admin: Release IP</h4>
            <form id="release-form" class="row g-2">
                @csrf
                <div class="col-md-8">
                    <input type="text" class="form-control" name="ip_address" placeholder="IP address" required>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-danger w-100" type="submit">Release</button>
                </div>
            </form>
            <div class="mt-3">
                <h5 class="h6">Current Votes</h5>
                <ul class="list-group" id="ip-list"></ul>
            </div>
            <div class="mt-3">
                <h5 class="h6">Vote History</h5>
                <ul class="list-group" id="ip-history"></ul>
            </div>
        </div>
    </div>
</div>
