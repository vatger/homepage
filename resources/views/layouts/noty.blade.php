@php
    use \Illuminate\Support\Facades\Session;
@endphp
<script>
  window.addEventListener("laravel_showNoty", (event) => {
      @foreach ($errors->all() as $error)
      window.showNoty("{{ $error }}", "error", 5000);
      @endforeach
      @if (Session::has('success'))
      window.showNoty("{{ Session::get('success') }}", "success", 5000);
      @endif
  });
</script>
