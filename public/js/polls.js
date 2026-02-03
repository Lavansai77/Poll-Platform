$(function () {
    let resultsInterval = null;

    function loadPoll(pollId, url) {
        $.get(url, function (html) {
            $('#poll-detail').html(html);
            bindPollActions(pollId);
            startResultsPolling(pollId);
            loadIpList(pollId);
        });
    }

    function bindPollActions(pollId) {
        $('#vote-form').on('submit', function (event) {
            event.preventDefault();
            const payload = $(this).serialize();

            $.post(`/api/polls/${pollId}/vote`, payload)
                .done(function (data) {
                    $('#vote-message').html(`<div class="text-success">${data.message}</div>`);
                    loadResults(pollId);
                    loadIpList(pollId);
                })
                .fail(function (xhr) {
                    const message = xhr.responseJSON?.message || 'Vote failed.';
                    $('#vote-message').html(`<div class="text-danger">${message}</div>`);
                });
        });

        $('#release-form').on('submit', function (event) {
            event.preventDefault();
            const payload = $(this).serialize();

            $.post(`/admin/polls/${pollId}/release`, payload)
                .done(function (data) {
                    $('#vote-message').html(`<div class="text-warning">${data.message}</div>`);
                    loadResults(pollId);
                    loadIpList(pollId);
                });
        });
    }

    function loadResults(pollId) {
        $.get(`/api/polls/${pollId}/results`, function (data) {
            const container = $('#poll-results');
            container.empty();
            data.results.forEach(function (result) {
                container.append(
                    `<div class="d-flex justify-content-between border rounded p-2 mb-2">
                        <span>${result.label}</span>
                        <span class="badge bg-primary">${result.votes}</span>
                    </div>`
                );
            });
        });
    }

    function loadIpList(pollId) {
        $.get(`/admin/polls/${pollId}/ips`, function (data) {
            const list = $('#ip-list');
            const history = $('#ip-history');
            list.empty();
            history.empty();

            data.active_votes.forEach(function (entry) {
                list.append(`<li class="list-group-item">${entry.ip_address} - ${entry.option} <small class="text-muted">${entry.voted_at}</small></li>`);
            });

            data.history.forEach(function (entry) {
                const releaseInfo = entry.released_at ? ` <small class="text-muted">released ${entry.released_at}</small>` : '';
                history.append(`<li class="list-group-item">${entry.ip_address} - ${entry.option} <small class="text-muted">${entry.voted_at}</small>${releaseInfo} <span class="badge bg-secondary">${entry.status}</span></li>`);
            });
        });
    }

    function startResultsPolling(pollId) {
        if (resultsInterval) {
            clearInterval(resultsInterval);
        }
        loadResults(pollId);
        resultsInterval = setInterval(function () {
            loadResults(pollId);
        }, 1000);
    }

    $('.poll-link').on('click', function (event) {
        event.preventDefault();
        const pollId = $(this).data('poll-id');
        const url = $(this).attr('href');
        loadPoll(pollId, url);
    });
});
