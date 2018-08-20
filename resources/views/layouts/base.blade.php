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
                @yield('content')
            </div>
        </main>
        @include('partials.footer')
    </div>
</div>
@include('partials.scripts')
</body>
</html>
