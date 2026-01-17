'use client';

import {
    ArrowRight,
    BarChart3,
    Crown,
    Database,
    Layout,
    Package,
    Palette,
    Users,
    Zap,
} from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

const mainFeatures = [
    {
        icon: Package,
        title: 'Curated Component Library',
        description:
            'Hand-picked blocks and templates for quality and reliability.',
    },
    {
        icon: Crown,
        title: 'Free & Premium Options',
        description:
            'Start free, upgrade to premium collections when you need more.',
    },
    {
        icon: Layout,
        title: 'Ready-to-Use Templates',
        description: 'Copy-paste components that just work out of the box.',
    },
    {
        icon: Zap,
        title: 'Regular Updates',
        description:
            'New blocks and templates added weekly to keep you current.',
    },
];

const _secondaryFeatures = [
    {
        icon: BarChart3,
        title: 'Multiple Frameworks',
        description:
            'React, Next.js, and Vite compatibility for flexible development.',
    },
    {
        icon: Palette,
        title: 'Modern Tech Stack',
        description: 'Built with shadcn/ui, Tailwind CSS, and TypeScript.',
    },
    {
        icon: Users,
        title: 'Responsive Design',
        description:
            'Mobile-first components for all screen sizes and devices.',
    },
    {
        icon: Database,
        title: 'Developer-Friendly',
        description:
            'Clean code, well-documented, easy integration and customization.',
    },
];

export function FeaturesSection() {
    return (
        <section id="features" className="py-24 sm:py-32 bg-muted/30">
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">
                {/* Section Header */}
                <div className="mx-auto max-w-2xl text-center mb-16">
                    <Badge variant="outline" className="mb-4">
                        Marketplace Features
                    </Badge>
                    <h2 className="text-3xl font-bold tracking-tight sm:text-4xl mb-4">
                        Everything you need to build amazing web applications
                    </h2>
                    <p className="text-lg text-muted-foreground">
                        Our marketplace provides curated blocks, templates,
                        landing pages, and admin dashboards to help you build
                        professional applications faster than ever.
                    </p>
                </div>

                {/* First Feature Section */}
                <div className="grid items-center gap-12 lg:grid-cols-2 lg:gap-8 xl:gap-16 mb-24">
                    {/* Left Image */}

                    {/* Right Content */}
                    <div className="space-y-6">
                        <div className="space-y-4">
                            <h3 className="text-2xl font-semibold tracking-tight text-balance sm:text-3xl">
                                Components that accelerate development
                            </h3>
                            <p className="text-muted-foreground text-base text-pretty">
                                Our curated marketplace offers premium blocks
                                and templates designed to save time and ensure
                                consistency across your admin projects.
                            </p>
                        </div>

                        <ul className="grid gap-4 sm:grid-cols-2">
                            {mainFeatures.map((feature, index) => (
                                <li
                                    key={index}
                                    className="group hover:bg-accent/5 flex items-start gap-3 p-2 rounded-lg transition-colors"
                                >
                                    <div className="mt-0.5 flex shrink-0 items-center justify-center">
                                        <feature.icon
                                            className="size-5 text-primary"
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <div>
                                        <h3 className="text-foreground font-medium">
                                            {feature.title}
                                        </h3>
                                        <p className="text-muted-foreground mt-1 text-sm">
                                            {feature.description}
                                        </p>
                                    </div>
                                </li>
                            ))}
                        </ul>

                        <div className="flex flex-col sm:flex-row gap-4 pe-4 pt-2">
                            <Button size="lg" className="cursor-pointer">
                                <a
                                    href="https://shadcnstore.com/templates"
                                    className="flex items-center"
                                >
                                    Browse Templates
                                    <ArrowRight
                                        className="ms-2 size-4"
                                        aria-hidden="true"
                                    />
                                </a>
                            </Button>
                            <Button
                                size="lg"
                                variant="outline"
                                className="cursor-pointer"
                            >
                                <a href="https://shadcnstore.com/blocks">
                                    View Components
                                </a>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
