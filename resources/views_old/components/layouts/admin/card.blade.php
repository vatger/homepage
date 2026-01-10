<div class="card shadow border-0 mt-4">
    <div class="row row-container p-4 border-bottom">
        {{ $slot ?? '' }}
    </div>
    <style>
        .row {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
</div>
