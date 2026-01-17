import type React from 'react';

export default function ContentLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <main className="container mx-auto w-full px-4 pt-6 pb-10 md:px-6">
            {children}
        </main>
    );
}
