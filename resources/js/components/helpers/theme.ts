import { useEffect, useState } from 'react';

const THEME_KEY = 'theme';

export function useTheme() {
    const [theme, setThemeState] = useState(() => {
        if (typeof window === 'undefined') return 'light';
        return localStorage.getItem(THEME_KEY) || 'light';
    });

    // sync with <html data-theme="...">
    useEffect(() => {
        document.documentElement.dataset.theme = theme;
        localStorage.setItem(THEME_KEY, theme);
    }, [theme]);

    const setTheme = (newTheme: string | ((arg0: string) => any)) => {
        if (typeof newTheme === 'function') {
            setThemeState((prev) => {
                const value = newTheme(prev);
                document.documentElement.dataset.theme = value;
                localStorage.setItem(THEME_KEY, value);
                return value;
            });
        } else {
            setThemeState(newTheme);
        }
    };

    return {
        theme,
        setTheme,
        resolvedTheme: theme, // mimics next-themes API
    };
}
