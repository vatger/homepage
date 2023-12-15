<div>

    @component('components.layouts.content',[
         'header' => 'Datenschutzbestimmungen',
         'links' => [
             route('landing') => config('app.name'),
             'Members',
             'Datenschutzbestimmungen',
             ]
     ])
    @endcomponent

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="alert alert-success mb-0" role="alert">
                    <h4 class="alert-heading">Herzlich Willkommen im Staff von VATSIM Germany</h4>
                    <p>Leider gibt es auch hier wieder einige Punkte zu beachten. Bitte lies dir die folgenden Zeilen aufmerksam durch und bestätige, dass du sie gelesen und verstanden hast.</p>
                    <p class="mb-0 border-top pt-3">Datenschutzbestimmungen</p>
                </div>
            </div>
            <div class="row">
                <a href="#" class="btn btn-pills btn-primary"> Primary </a>
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
</div>
