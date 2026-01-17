import type React from 'react';
import {LandingFooter} from './_components/footer';
import {LandingNavbar} from './_components/navbar';

export default function DashboardLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <div className=" bg-background">
            <LandingNavbar />
            <div className="pt-24">{children}</div>
            <LandingFooter />
        </div>
    );
}
