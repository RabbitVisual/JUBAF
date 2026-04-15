/**
 * Dark Mode & Theme Management
 *
 * Logic to sync Alpine.js state with localStorage and matchMedia.
 * Note: The critical anti-flicker script is located in the head of the layout file.
 */

(function () {
    const THEME_STORAGE_KEY = 'theme';

    // Helper to manually set theme if needed (e.g., from external unrelated JS)
    window.setTheme = function (theme) {
        localStorage.setItem(THEME_STORAGE_KEY, theme);
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        // Dispatch event for Alpine or other listeners to pick up if they are watching localStorage
        window.dispatchEvent(new Event('storage'));
    };

    // Helper to toggle
    window.toggleTheme = function () {
        const current = localStorage.getItem(THEME_STORAGE_KEY) === 'dark' ? 'dark' : 'light';
        window.setTheme(current === 'dark' ? 'light' : 'dark');
    };

    // Listen for system changes if no preference is saved
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (!localStorage.getItem(THEME_STORAGE_KEY)) {
                if (event.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
    }
})();
