<div>
    @component('components.layouts.content',[
        'header' => 'Support',
        'links' => [
            route('landing') => config('app.name'),'Help','Support'
            ]
    ])

    @endcomponent
    <section class="section">
        <div class="container">
            <div class="row ">
                <div class="alert alert-primary" role="alert"> Das Hochladen von Anlagen wird zurzeit noch nicht unterstützt. Wenn du eine Anlage hochladen möchstes verwende aktuell bitte noch unser <a target="_blank" href="https://support.vatsim-germany.org/open.php" class="alert-link">Ticketsystem</a>. </div>
                <div class="col-lg col-md mb-4">
                    <div class="card features rounded p-4 bg-white shadow position-relative overflow-hidden border-0 ">
                        <div class="card-body content">
                            <div class="mb-3">
                                <label class="form-label">Bitte wähle eine Supportkategorie<span class="text-danger">*</span></label>
                                <select wire:model.live="chosen_sup_type" class="form-select form-control" aria-label="CategoryChooser">
                                    @foreach($supporttype as $category )
                                        <option value="{{$category->id}}" @if($chosen_sup_type == $category->id) selected @endif>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bitte wähle einen Bereich<span class="text-danger">*</span></label>
                                <select wire:model.live="chosen_area" class="form-select form-control" aria-label="AreaChooser">
                                    @foreach($areas as $area )
                                        @if(in_array($chosen_sup_type,$area->supporttypes))
                                            <option value="{{$area->id}}">{{$area->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Dein Name <span class="text-danger">*</span></label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                            <input name="name" id="name" type="text" class="form-control ps-5" @if($user) value="{{$user->name}}" @endif placeholder="Max Mustermann">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Deine E-Mail <span class="text-danger">*</span></label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="mail" class="fea icon-sm icons"></i>
                                            <input name="email" id="email" type="email" class="form-control ps-5" @if($user) value="{{$user->email}}" @endif placeholder="mail@me.de">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Deine VATSIM-ID <span class="text-danger">*</span></label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="id" class="fea icon-sm icons"></i>
                                            <input name="id" id="id" type="text" class="form-control ps-5" @if($user) value="{{$user->id}}" @endif placeholder="1000001">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Betreff</label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="book" class="fea icon-sm icons"></i>
                                            <input name="subject" id="subject" class="form-control ps-5" placeholder="Fasse dein Anliegen kurz zusammen">
                                        </div>
                                    </div>
                                </div><!--end col-->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Beschreibung</label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="message-circle" class="fea icon-sm icons"></i>
                                            <textarea name="comments" id="comments" rows="4" class="form-control ps-5" placeholder="Deine Nachricht an uns"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end row-->
                            <div class="row">

                            </div><!--end row-->
                            <div class="row">
                                <div class="col-sm-12">
                                    {{--}}<input type="submit" id="submit" name="send" class="btn btn-primary" value="Absenden">{{--}}
                                </div><!--end col-->
                            </div><!--end row-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



