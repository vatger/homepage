'use client';

import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import type React from 'react';
import { AppSidebar } from './_components/app-sidebar';
import { Breadcrumbs } from './_components/breadcrumbs';
import { SiteHeader } from './_components/site-header';

export default function DashboardLayout({ children }: { children: React.ReactNode }) {
    return (
        <SidebarProvider
            style={
                {
                    '--sidebar-width': '16rem',
                    '--sidebar-width-icon': '3rem',
                    '--header-height': 'calc(var(--spacing) * 14)',
                } as React.CSSProperties
            }
        >
            <AppSidebar
                variant={'inset'}
                collapsible={'offcanvas'}
                side={'left'}
            />
            <SidebarInset>
                <SiteHeader />
                <div className="flex flex-1 flex-col">
                    <div className="p-4 md:gap-6 md:p-6">
                        <Breadcrumbs />
                        {children}
                    </div>
                </div>
            </SidebarInset>
        </SidebarProvider>
    );
}
