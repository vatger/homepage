'use client';

import {ChevronRight, Home} from 'lucide-react';
import Link from 'next/link';
import {usePathname} from 'next/navigation';

function formatSegment(segment: string) {
    return segment
        .replace(/-/g, ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
}

export function Breadcrumbs() {
    const pathname = usePathname().replace('/dashboard', '');
    const segments = pathname.split('/').filter(Boolean);

    if (pathname === '') {
        return null;
    }

    return (
        <nav aria-label="Breadcrumb" className="pb-5">
            <ol className="flex items-center gap-1 text-sm text-muted-foreground">
                {/* Home */}
                <li>
                    <Link
                        href="/dashboard"
                        className="flex items-center gap-1 hover:text-foreground transition-colors"
                    >
                        <Home className="h-4 w-4" />
                    </Link>
                </li>

                {segments.map((segment, index) => {
                    const href = `/${segments.slice(0, index + 1).join('/')}`;
                    const isLast = index === segments.length - 1;

                    return (
                        <li key={href} className="flex items-center gap-1">
                            <ChevronRight className="h-4 w-4 text-muted-foreground/60" />

                            {isLast ? (
                                <span className="font-medium text-foreground">
                                    {formatSegment(segment)}
                                </span>
                            ) : (
                                <Link
                                    href={href}
                                    className="hover:text-foreground transition-colors"
                                >
                                    {formatSegment(segment)}
                                </Link>
                            )}
                        </li>
                    );
                })}
            </ol>
        </nav>
    );
}
