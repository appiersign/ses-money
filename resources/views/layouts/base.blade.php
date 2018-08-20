<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    @include('partials.head')
</head>
<body class="app">
<div id="loader">
    <div class="spinner"></div>
</div>
<script type="text/javascript">window.addEventListener('load', () => {
        const loader = document.getElementById('loader');
        setTimeout(() => {
            loader.classList.add('fadeOut');
        }, 300);
    });
</script>
<div>
    @include('partials.sidebar')
    <div class="page-container">
        @include('partials.header')
        <main class="main-content bgc-grey-100">
            <div id="mainContent">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endforeach
                    @elseif(session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                @yield('content')
            </div>
        </main>
        @include('partials.footer')
    </div>
</div>
@include('partials.scripts')
</body>
</html>
