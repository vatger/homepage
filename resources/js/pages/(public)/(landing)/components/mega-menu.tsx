'use client';

import { Building2, Crown, Package, Rocket } from 'lucide-react';

const menuSections = [
    {
        title: 'Browse Products',
        items: [
            {
                title: 'Free Blocks',
                description: 'Essential UI components and sections',
                icon: Package,
                href: '#free-blocks',
            },
            {
                title: 'Premium Templates',
                description: 'Complete page templates and layouts',
                icon: Crown,
                href: '#premium-templates',
            },
        ],
    },
    {
        title: 'Categories',
        items: [
            {
                title: 'E-commerce',
                description: 'Online store admin panels and components',
                icon: Building2,
                href: '#ecommerce',
            },
            {
                title: 'SaaS Dashboards',
                description: 'Application admin interfaces',
                icon: Rocket,
                href: '#saas-dashboards',
            },
        ],
    },
];

export function MegaMenu() {
    return (
        <div className="w-[700px] max-w-[95vw] p-4">
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-12">
                {menuSections.map((section) => (
                    <div key={section.title} className="space-y-4 lg:space-y-6">
                        {/* Section Header */}
                        <h3 className="text-sm font-medium text-muted-foreground uppercase tracking-wide">
                            {section.title}
                        </h3>

                        {/* Section Links */}
                        <div className="space-y-3 lg:space-y-4">
                            {section.items.map((item) => (
                                <a
                                    key={item.title}
                                    href={item.href}
                                    className="group block space-y-1 lg:space-y-2 hover:bg-accent rounded-md p-2 lg:p-3 -mx-2 lg:-mx-3 transition-colors my-0"
                                >
                                    <div className="flex items-center gap-2 lg:gap-3">
                                        <item.icon className="w-4 h-4 text-muted-foreground group-hover:text-primary transition-colors" />
                                        <span className="text-sm font-medium text-foreground group-hover:text-primary transition-colors">
                                            {item.title}
                                        </span>
                                    </div>
                                    <p className="text-xs text-muted-foreground leading-relaxed ml-6 lg:ml-7">
                                        {item.description}
                                    </p>
                                </a>
                            ))}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
