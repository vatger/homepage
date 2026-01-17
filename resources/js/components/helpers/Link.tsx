import { Link as InertiaLink } from '@inertiajs/react';

// @ts-ignore
export default function Link({ href, children, ...props }) {
    return (
        <InertiaLink
            href={href}
            {...props}
        >
            {children}
        </InertiaLink>
    );
}
