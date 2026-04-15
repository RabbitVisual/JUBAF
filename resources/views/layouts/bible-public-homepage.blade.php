{{--
    Bíblia pública com o mesmo shell visual da homepage (navbar, gradiente, rodapé).
    Requer módulo Homepage (navbar/footer). Conteúdo da página: @section('bible_public_content').
--}}
@extends('homepage::layouts.homepage')

@section('content')
    @include('homepage::layouts.navbar-homepage')

    <div
        class="min-h-screen bg-gradient-to-br from-slate-200 via-slate-100 to-blue-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        @if (module_enabled('Avisos'))
            <x-avisos::avisos-por-posicao posicao="topo" :container="true" />
        @endif

        @yield('bible_public_content')
    </div>

    @include('homepage::layouts.footer-homepage')

    <button id="backToTop" type="button"
        class="fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-r from-[#0047AB] to-blue-800 dark:from-blue-600 dark:to-slate-800 text-white rounded-full shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 opacity-0 invisible z-50 flex items-center justify-center group"
        aria-label="Voltar ao topo">
        <x-icon name="arrow-up" class="w-6 h-6 group-hover:-translate-y-1 transition-transform" />
    </button>

    <script>
        (function() {
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                    anchor.addEventListener('click', function(e) {
                        var href = this.getAttribute('href');
                        if (href !== '#' && href.length > 1) {
                            e.preventDefault();
                            var target = document.querySelector(href);
                            if (target) {
                                window.scrollTo({
                                    top: target.offsetTop - 80,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    });
                });
                var backToTopBtn = document.getElementById('backToTop');
                if (backToTopBtn) {
                    window.addEventListener('scroll', function() {
                        if (window.pageYOffset > 300) {
                            backToTopBtn.classList.remove('opacity-0', 'invisible');
                            backToTopBtn.classList.add('opacity-100', 'visible');
                        } else {
                            backToTopBtn.classList.add('opacity-0', 'invisible');
                            backToTopBtn.classList.remove('opacity-100', 'visible');
                        }
                    });
                    backToTopBtn.addEventListener('click', function() {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });
                }
            });
        })();
    </script>
@endsection
