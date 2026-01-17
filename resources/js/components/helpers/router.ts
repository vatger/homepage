import { router, usePage } from '@inertiajs/react';
import { UrlMethodPair } from 'node_modules/@inertiajs/core/types/types';

export function useRouter() {
    const { url } = usePage();

    return {
        push: (href: string | URL | UrlMethodPair, options = {}) => router.visit(href, options),
        replace: (href: string | URL | UrlMethodPair, options = {}) =>
            router.visit(href, { ...options, replace: true }),
        back: () => window.history.back(),
        reload: () => router.reload(),
        pathname: url.split('?')[0],
        query: Object.fromEntries(new URLSearchParams(url.split('?')[1])),
    };
}
