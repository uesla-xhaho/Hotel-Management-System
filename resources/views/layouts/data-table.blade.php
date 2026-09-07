@php
    $tableId = $id ?? null;
    $tableClass = $class ?? '';
    $searchPlaceholder = $searchPlaceholder ?? 'Search';
    $pageLength = $pageLength ?? 10;
    $order = $order ?? null;
    $responsive = $responsive ?? true;
    $scrollX = $scrollX ?? false;
    $cookieKey = $cookieKey ?? null;
@endphp

<div class="hm-table-card">
    <div class="hm-table-rail">
        <table
            @if ($tableId) id="{{ $tableId }}" @endif
            class="hm-table table table-borderless align-middle {{ $tableClass }}"
            data-hm-datatable="true"
            data-hm-search-placeholder="{{ $searchPlaceholder }}"
            data-hm-page-length="{{ $pageLength }}"
            data-hm-responsive="{{ $responsive ? 'true' : 'false' }}"
            data-hm-scroll-x="{{ $scrollX ? 'true' : 'false' }}"
            @if ($cookieKey) data-hm-cookie-key="{{ $cookieKey }}" @endif
            @if (!is_null($order)) data-hm-order="{{ $order }}" @endif
        >
            {{ $slot }}
        </table>
    </div>
</div>
