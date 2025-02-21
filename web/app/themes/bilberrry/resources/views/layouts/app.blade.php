<!doctype html>
<html @php(language_attributes())>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php(do_action('get_header'))
  @php(wp_head())
</head>

<body @php(body_class())>
@php(wp_body_open())

<div id="app">

  @include('sections.header')

  <main id="main" class="main">
    <div class="container">
      @yield('content')
      <div class="mt-5">
        @php (get_sorted_jobs_by_title())>
      </div>
    </div>
  </main>

  @include('sections.footer')
</div>

@php(do_action('get_footer'))
@php(wp_footer())
</body>
</html>
