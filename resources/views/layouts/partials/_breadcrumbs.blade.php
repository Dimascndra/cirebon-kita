{{-- Breadcrumb Component --}}
<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        {{-- Title --}}
        <div class="d-flex align-items-center flex-wrap mr-1">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ $title ?? 'Dashboard' }}</h5>
        </div>

        {{-- Toolbar / Actions --}}
        @if (isset($action))
            <div class="d-flex align-items-center">
                {{ $action }}
            </div>
        @endif
    </div>
</div>
