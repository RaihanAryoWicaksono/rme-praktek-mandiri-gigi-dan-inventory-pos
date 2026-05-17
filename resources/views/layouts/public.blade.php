<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'drg. Moh Ariv Widodo Dental Clinic')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body class="bg-white text-slate-900 antialiased font-sans">

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script>
        // Mobile menu toggle
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Sticky navbar glass effect on scroll
        const navbarShell = document.getElementById('navbar-shell');
        if (navbarShell) {
            const onScroll = () => {
                if (window.scrollY > 20) {
                    navbarShell.classList.add('shadow-md', 'backdrop-blur-md', 'bg-white/95');
                    navbarShell.classList.remove('bg-white/70');
                } else {
                    navbarShell.classList.remove('shadow-md', 'backdrop-blur-md', 'bg-white/95');
                    navbarShell.classList.add('bg-white/70');
                }
            };
            window.addEventListener('scroll', onScroll, { passive: true });
        }
    </script>
    @stack('scripts')
</body>
</html>
