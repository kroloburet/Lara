@props(['reports'])

<!--- Bug Reports List --->
@forelse($reports as $report)
    @php
        $replacer = '- no data -';
    @endphp

    <div class="Paginator_item">
        <div class="Paginator_item-prev red-text">⚠</div>

        <div class="Paginator_item-attr">
            {{ consumerDateTimeFormat($report->created_at, 'admin') }}
        </div>

        <div class="Paginator_item-attr">
            {!! $report->status_text !!}
        </div>

        <div class="Paginator_item-control">
            @can('superAdmin')
                <a class="Paginator_item-control-btn detailsMoreButton">More</a>

                <a class="Paginator_item-control-btn delReport" data-id="{{ $report->id }}">It's fixed</a>
            @endcan
        </div>

        <div class="paginatorItemControlToggle"></div>

        @can('superAdmin')
            <div class="Paginator_item-details detailsMoreContainer">
                <div><b>Page: </b><a href="{{ url($report->page_url) }}" target="_blank">{{ $report->page_url }}</a></div>
                <div><b>Path: </b>{{ $report->url ?? $replacer }}</div>
                <div><b>File: </b>{{ $report->file ?? $replacer }}</div>
                <div><b>Status Text: </b>{!! $report->status_text ?? $replacer !!}</div>
                <div><b>Line: </b>{{ $report->line ?? $replacer }}</div>
                <div><b>Method: </b>{{ $report->method ?? $replacer }}</div>
                <div><b>Status: </b>{{ $report->status ?? $replacer }}</div>
                <div><b>Header: </b>{{ $report->server_header ?? $replacer }}</div>
                <div><b>User Agent: </b>{{ $report->user_agent ?? $replacer }}</div>
                <div><b>IP: </b>{{ $report->ip ?? $replacer }}</div>
                <div><b>Stack Trace: </b><pre>{{ $report->stack_trace ?? $replacer }}</pre></div>
            </div>
        @endcan
    </div>
@empty
    <div class="Paginator_no-result">
        {!! __('base.no_results') !!}
    </div>
@endforelse
