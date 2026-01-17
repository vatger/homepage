import { Head as InertiaHead } from '@inertiajs/react';

// @ts-ignore
export default function Head({ children, title }) {
    return <InertiaHead title={title}>{children}</InertiaHead>;
}
