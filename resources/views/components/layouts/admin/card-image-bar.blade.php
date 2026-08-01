@props([
    'bg_img',
    'm_img',
    'title',
    'subtitle'
])

<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 overflow-hidden p-4"
             style="background: url('{{ $bg_img ?? '' }}') center;background-size: cover;">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-8">
                    <div class="text-center bg-white p-4 rounded-2xl">
                        <img src="{{ $m_img }}" class="rounded-circle shadow avatar avatar-md-md"
                             alt="">
                        <h5 class="mt-3 mb-0">{{ $title }}</h5>
                        <small class="text-muted">{{ $subtitle }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->
