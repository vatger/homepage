<div>
    @component('components.layouts.content',[
        'header' => 'Support',
        'links' => [
            route('landing') => config('app.name'),'Help','Support'
            ]
    ])

    @endcomponent
    <script src="https://js.hcaptcha.com/1/api.js?hl=en"></script>
    <section class="section">
        <div class="container">
            <div class="row ">

                <div class="col-lg col-md mb-4">
                    <div class="card features rounded p-4 bg-white shadow position-relative overflow-hidden border-0 ">
                        <div class="card-body content">
                            @if($selected_type && ($selected_type->system == "V" || property_exists($selected_type, 'public_url')))
                                <div class="alert bg-soft-warning fw-medium" role="alert">
                                    <i data-feather="alert-triangle" class=" fea fs-5 align-middle me-1"></i>
                                    @lang('support.text-no-credentials')
                                </div>
                                @if($selected_type->public_url)
                                    <div class="alert alert-info" role="alert"> @lang('support.text-check-board')
                                        <a target='_blank' class="alert-link" href={{ $selected_type->public_url }}>
                                            @lang('support.text-here')
                                        </a>.
                                    </div>
                                @endif
                            @endif
                            <div class="mb-3">
                                <label class="form-label text-primary">@lang('support.text-choose-area')<span class="text-danger">*</span></label>
                                <select wire:model.live="chosen_area" class="form-select form-control" aria-label="AreaChooser">
                                    <option value="0" @if($chosen_area == 0) selected @endif></option>
                                    @foreach($areas as $area)
                                        <option value="{{$area->id}}">{{$area->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($chosen_area != 0)
                                <div class="mb-3">
                                    <label class="form-label text-primary">@lang('support.text-choose-cat')<span class="text-danger">*</span></label>
                                    <select wire:model.live="chosen_sup_type" class="form-select form-control" aria-label="CategoryChooser">
                                        <option value="0" @if($chosen_sup_type == 0) selected @endif></option>
                                        @if($selected_area != null)
                                            @foreach($selected_area->types as $type)
                                                <option value="{{$type->id}}" @if($chosen_sup_type == $type->id) selected @endif>{{$type->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary">@lang('support.text-name')<span class="text-danger">*</span></label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="user" class="fea fea-primary icon-sm icons"></i>
                                            <input wire:model="name" name="name" id="name" type="text" class="form-control ps-5" @if($user) disabled @endif placeholder="Max Mustermann">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary">@lang('support.text-mail')<span class="text-danger">*</span></label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="mail" class="fea fea-primary icon-sm icons"></i>
                                            <input wire:model="mail" name="email" id="email" type="email" class="form-control ps-5" placeholder="mail@me.de">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary">@lang('support.text-cid')</label>
                                        <div class="form-icon position-relative">
                                            <input wire:model="cid" name="id" id="id" type="text" class="form-control ps-5" @if($user) disabled @endif placeholder="1000001">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label text-primary">@lang('support.text-subject')</label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="book" class="fea fea-primary icon-sm icons"></i>
                                            <input wire:model="subject" name="subject" id="subject" class="form-control ps-5">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label text-primary">@lang('support.text-content')</label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="message-circle" class="fea fea-primary icon-sm icons"></i>
                                            <textarea wire:model="content" name="comments" id="comments" rows="4" class="form-control ps-5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end row-->
                            <div class="row">
                                <x-captcha fieldName="token" />
                            </div><!--end row-->
                            <div class="row">
                                <div class="col-sm-12">
                                    <button name="send" wire:click="send()" wire:loading.attr="disabled" class="btn btn-soft-success">
                                        <i data-feather="plus" class="fea fea-primary"></i>@lang('support.text-send')
                                    </button>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
