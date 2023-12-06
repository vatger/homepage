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
                            @if($chosen_area=='1' && ($chosen_sup_type =='1' || $chosen_sup_type =='2' ))
                                <div class="alert bg-soft-warning fw-medium" role="alert"> <i data-feather="alert-triangle" class=" fea fs-5 align-middle me-1"></i>@lang('support.text-no-credentials')</div>
                                <div class="alert alert-info" role="alert"> @lang('support.text-check-board') <a target='_blank' class="alert-link" href={{config('support.vikunja_tech_board')}}>@lang('support.text-here')</a>. </div>
                            @endif
                            <div class="mb-3">
                            <label class="form-label">@lang('support.text-choose-area')<span class="text-danger">*</span></label>
                            <select wire:model.live="chosen_area" class="form-select form-control" aria-label="AreaChooser">
                                <option selected></option>
                                @foreach($areas as $area )
                                        <option value="{{$area->id}}">{{$area->name}}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="mb-3">
                                <label class="form-label">@lang('support.text-choose-cat')<span class="text-danger">*</span></label>
                                    <select wire:model.live="chosen_sup_type" class="form-select form-control" aria-label="CategoryChooser">
                                    <option selected></option>
                                    @foreach($supporttype as $category )
                                        @if(in_array($chosen_area,$category->areas))
                                        <option value="{{$category->id}}" @if($chosen_sup_type == $category->id) selected @endif>{{$category->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">@lang('support.text-name')<span class="text-danger">*</span></label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                            <input wire:model="name" name="name" id="name" type="text" class="form-control ps-5" @if($user) disabled @endif placeholder="Max Mustermann">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">@lang('support.text-mail')<span class="text-danger">*</span></label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="mail" class="fea icon-sm icons"></i>
                                            <input wire:model="mail" name="email" id="email" type="email" class="form-control ps-5" placeholder="mail@me.de">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">@lang('support.text-cid')</label>
                                        <div class="form-icon position-relative">
                                            <input wire:model="cid" name="id" id="id" type="text" class="form-control ps-5" @if($user) disabled @endif placeholder="1000001">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">@lang('support.text-subject')</label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="book" class="fea icon-sm icons"></i>
                                            <input wire:model="subject" name="subject" id="subject" class="form-control ps-5">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">@lang('support.text-content')</label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="message-circle" class="fea icon-sm icons"></i>
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
                                    <button name="send" wire:click="send()" wire:loading.attr="disabled" class="btn btn-soft-success" >
                                        <i data-feather="plus" class="fea"></i>@lang('support.text-send')
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



