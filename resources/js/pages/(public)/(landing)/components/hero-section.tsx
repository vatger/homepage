'use client';

import Link from 'next/link';
import {Button} from '@/components/ui/button';

export function HeroSection() {
    return (
        <section className="relative pt-36 pb-10 sm:pt-64 sm:pb-20">
            <div className="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                {/* Background accents */}

                <div className="grid items-center gap-10 lg:grid-cols-12 lg:gap-12">
                    {/* Copy */}
                    <div className="mx-auto max-w-2xl text-center lg:col-span-6 lg:text-left">
                        <h1 className="mb-6 text-4xl font-bold tracking-tight sm:text-6xl lg:text-7xl text-secondary-50">
                            As Real As It Gets
                        </h1>

                        <p className="mb-10 text-lg sm:text-xl text-secondary-200">
                            Delivering authentic air traffic control in Germany
                            and operated according to real-world procedures for
                            a realistic flight simulation experience.
                        </p>

                        <Button variant={'accent'} size="lg" asChild>
                            <Link href="/getting-started">Get Started Now</Link>
                        </Button>
                    </div>
                </div>
            </div>
        </section>
    );
}
