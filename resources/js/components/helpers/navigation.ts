import { router, usePage } from '@inertiajs/react';
import { UrlMethodPair } from 'node_modules/@inertiajs/core/types/types';
import { useMemo } from 'react';

export function useRouter() {
    const { url } = usePage();

    return useMemo(
        () => ({
            push: (href: string | URL | UrlMethodPair, options = {}) => router.visit(href, options),
            replace: (href: string | URL | UrlMethodPair, options = {}) =>
                router.visit(href, { ...options, replace: true }),
            back: () => window.history.back(),
            reload: () => router.reload(),
            // deprecated Next 13 Router extras
            prefetch: () => {},
            refresh: () => router.reload(),
        }),
        [url],
    );
}

export function usePathname() {
    const { url } = usePage();
    return useMemo(() => url.split('?')[0], [url]);
}

export function useSearchParams() {
    const { url } = usePage();
    return useMemo(() => {
        const params = new URLSearchParams(url.split('?')[1] || '');
        return {
            get: (key: string) => params.get(key),
            has: (key: string) => params.has(key),
            // optional iterator
            entries: () => params.entries(),
        };
    }, [url]);
}
