<div class="card border-0 rounded shadow">
    <div class="card-body">
        <h5 class="text-md-start text-center mb-0">Regionalgroup Detail :</h5>
        <div class="d-flex align-items-center mt-3">
            <div class="flex-1">
                <h6 class="text-primary mb-0">FIR :</h6>
                <a href="javascript:void(0)" class="text-muted">{{ $regionalgroup->fir?->name }}</a>
            </div>
        </div>
        <div class="d-flex align-items-center mt-3">
            <div class="flex-1">
                <h6 class="text-primary mb-0">RG :</h6>
                <a href="javascript:void(0)" class="text-muted">{{ $regionalgroup->name }}</a>
            </div>
        </div>
        <div class="d-flex align-items-center mt-3">
            <div class="flex-1">
                <h6 class="text-primary mb-0">Chief :</h6>
                <a href="javascript:void(0)" class="text-muted">{{ $regionalgroup->chief?->username }}</a>
            </div>
        </div>
        <div class="d-flex align-items-center mt-3">
            <div class="flex-1">
                <h6 class="text-primary mb-0">Deputy :</h6>
                <a href="javascript:void(0)" class="text-muted">{{ $regionalgroup->deputy?->username }}</a>
            </div>
        </div>
        <div class="d-flex align-items-center mt-3">
            <div class="flex-1">
                <h6 class="text-primary mb-0">Members :</h6>
                <a href="javascript:void(0)" class="text-muted">{{ count($regionalgroup->members) }}</a>
            </div>
        </div>
        <div class="d-flex align-items-center mt-3">
            <div class="flex-1">
                <h6 class="text-primary mb-0">Guests :</h6>
                <a href="javascript:void(0)" class="text-muted">{{ count($regionalgroup->guests) }}</a>
            </div>
        </div>
    </div>
</div>
