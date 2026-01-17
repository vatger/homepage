'use client';

import { LayoutDashboard, CalendarDays } from 'lucide-react';
import Link from 'next/link';
import type * as React from 'react';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { Logo } from '@/components/vatger/logo';
import { NavMain } from './nav-main';
import { NavUser } from './nav-user';

const data = {
    user: {
        name: 'ShadcnStore',
        email: 'store@example.com',
        avatar: '',
    },
    navGroups: [
        {
            label: 'Dashboards',
            items: [
                {
                    title: 'Dashboard 1',
                    url: '/dashboard',
                    icon: LayoutDashboard,
                },
                {
                    title: 'Bookings',
                    url: '/dashboard/bookings',
                    icon: CalendarDays,
                },
            ],
        },
    ],
};

export function AppSidebar({ ...props }: React.ComponentProps<typeof Sidebar>) {
    return (
        <Sidebar {...props}>
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard">
                                <Logo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent>
                {data.navGroups.map((group) => (
                    <NavMain
                        key={group.label}
                        label={group.label}
                        items={group.items}
                    />
                ))}
            </SidebarContent>
            <SidebarFooter>
                <NavUser user={data.user} />
            </SidebarFooter>
        </Sidebar>
    );
}
