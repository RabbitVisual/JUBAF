@auth
<script>
    window.toggleSidebar = window.toggleSidebar || function () {
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (sidebar && overlay) {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('sidebar-overlay');
        if (overlay) {
            overlay.addEventListener('click', function () {
                window.toggleSidebar?.();
            });
        }
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            const mobileSidebar = document.getElementById('mobile-sidebar');
            if (mobileSidebar && !mobileSidebar.classList.contains('-translate-x-full')) {
                window.toggleSidebar?.();
            }
        });
    });

    window.addEventListener('themeChanged', function (e) {
        const isDark = e.detail && e.detail.theme === 'dark';
        const sunIcon = document.getElementById('theme-icon-sun');
        const moonIcon = document.getElementById('theme-icon-moon');
        if (sunIcon && moonIcon) {
            if (isDark) {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            } else {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            }
        }
    });

    window.handleLogout = window.handleLogout || function () {
        const logoutUrl = @json(route('logout'));
        const logoutUrlGet = @json(route('logout.get'));
        const loginUrl = @json(route('login'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            fetch(logoutUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            }).then(function (response) {
                if (response.ok || response.redirected) {
                    window.location.href = loginUrl;
                } else {
                    window.location.href = logoutUrlGet;
                }
            }).catch(function () {
                window.location.href = logoutUrlGet;
            });
        } else {
            window.location.href = logoutUrlGet;
        }
    };
</script>

@if(($erpShell ?? '') === 'diretoria' && module_enabled('Chat') && \Illuminate\Support\Facades\Route::has('diretoria.chat.api.presence') && auth()->user()->hasAnyRole(jubaf_chat_agent_role_names()))
    <script>
        (function () {
            var url = @json(route('diretoria.chat.api.presence'));
            var token = document.querySelector('meta[name="csrf-token"]');
            function ping() {
                if (!token) return;
                fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': token.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: '{}'
                }).catch(function () {});
            }
            ping();
            setInterval(ping, 60000);
        })();
    </script>
@endif
@endauth
