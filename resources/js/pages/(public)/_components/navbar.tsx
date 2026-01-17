'use client';

import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import {
    NavigationMenu,
    NavigationMenuContent,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    NavigationMenuTrigger,
} from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Logo } from '@/components/vatger/logo';
import { ChevronDown, ExternalLink, Github, Menu, Moon, Sun, X } from 'lucide-react';
import Image from 'next/image';
import Link from 'next/link';
import type React from 'react';
import { useState } from 'react';
import { ModeToggle } from '../../../components/theme-toggle';

// Smooth scroll function
const smoothScrollTo = (targetId: string) => {
    if (targetId.startsWith('#')) {
        const element = document.querySelector(targetId);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });
        }
    }
};

const components: { title: string; href: string; description: string }[] = [
    {
        title: 'Restricted Stations',
        href: '/restricted-stations',
        description: 'some stations',
    },
    {
        title: 'Hover Card',
        href: '/docs/primitives/hover-card',
        description: 'For sighted users to preview content available behind a link.',
    },
    {
        title: 'Progress',
        href: '/docs/primitives/progress',
        description:
            'Displays an indicator showing the completion progress of a task, typically displayed as a progress bar.',
    },
    {
        title: 'Scroll-area',
        href: '/docs/primitives/scroll-area',
        description: 'Visually or semantically separates content.',
    },
    {
        title: 'Tabs',
        href: '/docs/primitives/tabs',
        description: 'A set of layered sections of content—known as tab panels—that are displayed one at a time.',
    },
    {
        title: 'Tooltip',
        href: '/docs/primitives/tooltip',
        description:
            'A popup that displays information related to an element when the element receives keyboard focus or the mouse hovers over it.',
    },
];

export function LandingNavbar() {
    const [isOpen, setIsOpen] = useState(false);
    const [solutionsOpen, setSolutionsOpen] = useState(false);

    return (
        <header className="bg-background/90 supports-backdrop-filter:bg-background/85 fixed top-0 z-50 w-full border-b backdrop-blur-xl">
            <div className="container mx-auto flex h-20 items-center justify-between px-4 sm:px-6 lg:px-6">
                {/* Logo */}
                <div className="flex items-center space-x-2">
                    <Link
                        href="/"
                        className="flex cursor-pointer items-center space-x-2"
                        rel="noopener noreferrer"
                    >
                        <div className="w-42 relative h-20">
                            <Logo />
                        </div>
                    </Link>
                </div>

                {/* Desktop Navigation */}
                <NavigationMenu
                    viewport={false}
                    className="hidden xl:block"
                >
                    <NavigationMenuList className="flex-wrap">
                        <NavigationMenuItem>
                            <NavigationMenuTrigger>Pilots</NavigationMenuTrigger>
                            <NavigationMenuContent>
                                <ul className="w-50 grid gap-4">
                                    <li>
                                        <NavigationMenuLink asChild>
                                            <Link
                                                href="https://pmp.vatsim-germany.org/"
                                                target="_blank"
                                                className="flex-row items-center gap-2"
                                            >
                                                Pilot Mentoring <ExternalLink />
                                            </Link>
                                        </NavigationMenuLink>
                                        <NavigationMenuLink asChild>
                                            <Link
                                                href="https://tours.vatsim-germany.org/"
                                                target="_blank"
                                                className="flex-row items-center gap-2"
                                            >
                                                Tours <ExternalLink />
                                            </Link>
                                        </NavigationMenuLink>
                                    </li>
                                </ul>
                            </NavigationMenuContent>
                        </NavigationMenuItem>
                        <NavigationMenuItem>
                            <NavigationMenuTrigger>Controllers</NavigationMenuTrigger>
                            <NavigationMenuContent>
                                <ul className="w-50 grid gap-4">
                                    <li>
                                        <NavigationMenuLink asChild>
                                            <Link href="/restricted-stations">Stations</Link>
                                        </NavigationMenuLink>
                                    </li>
                                </ul>
                            </NavigationMenuContent>
                        </NavigationMenuItem>
                        <NavigationMenuItem className="hidden md:block">
                            <NavigationMenuTrigger>Community</NavigationMenuTrigger>
                            <NavigationMenuContent>
                                <ul className="w-50 grid gap-4">
                                    <li>
                                        <NavigationMenuLink asChild>
                                            <Link
                                                href="https://community.vatsim.net/"
                                                target="_blank"
                                                className="flex-row items-center gap-2"
                                            >
                                                Discord <ExternalLink />
                                            </Link>
                                        </NavigationMenuLink>
                                        <NavigationMenuLink asChild>
                                            <Link
                                                href="https://board.vatsim-germany.org/"
                                                target="_blank"
                                                className="flex-row items-center gap-2"
                                            >
                                                Board <ExternalLink />
                                            </Link>
                                        </NavigationMenuLink>
                                    </li>
                                </ul>
                            </NavigationMenuContent>
                        </NavigationMenuItem>
                        <NavigationMenuItem>
                            <NavigationMenuLink asChild>
                                <Link href="/help">Help</Link>
                            </NavigationMenuLink>
                        </NavigationMenuItem>
                    </NavigationMenuList>
                </NavigationMenu>

                {/* Desktop CTA */}
                <div className="hidden items-center space-x-2 xl:flex">
                    <ModeToggle />
                    <Button
                        variant={'default'}
                        asChild
                        className="cursor-pointer"
                    >
                        <Link href="/dashboard/account">Sign In</Link>
                    </Button>
                </div>

                {/* Mobile Menu */}
                <Sheet
                    open={isOpen}
                    onOpenChange={setIsOpen}
                >
                    <SheetTrigger
                        asChild
                        className="xl:hidden"
                    >
                        <Button
                            variant="ghost"
                            size="icon"
                            className="cursor-pointer"
                        >
                            <Menu className="h-5 w-5" />
                            <span className="sr-only">Toggle menu</span>
                        </Button>
                    </SheetTrigger>
                    <SheetContent
                        side="right"
                        className="flex w-full flex-col gap-0 overflow-hidden p-0 sm:w-[400px] [&>button]:hidden"
                    >
                        <div className="flex h-full flex-col">
                            {/* Header */}
                            <SheetHeader className="space-y-0 border-b p-4 pb-2">
                                <div className="flex items-center gap-2">
                                    <SheetTitle className="text-lg font-semibold">
                                        <Link
                                            href="http://localhost:3000"
                                            className="flex cursor-pointer items-center space-x-2"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            <Image
                                                src={'static/assets/logo.svg'}
                                                width={140}
                                                height={40}
                                                alt="vatger Logo"
                                            />
                                        </Link>
                                    </SheetTitle>
                                    <div className="ml-auto flex items-center gap-2">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            onClick={() => {}}
                                            className="h-8 w-8 cursor-pointer"
                                        >
                                            <Moon className="h-4 w-4 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0" />
                                            <Sun className="absolute h-4 w-4 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100" />
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            asChild
                                            className="h-8 w-8 cursor-pointer"
                                        >
                                            <a
                                                href="https://github.com/silicondeck/shadcn-dashboard-landing-template"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                aria-label="GitHub Repository"
                                            >
                                                <Github className="h-4 w-4" />
                                            </a>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            onClick={() => setIsOpen(false)}
                                            className="h-8 w-8 cursor-pointer"
                                        >
                                            <X className="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </SheetHeader>

                            {/* Navigation Links */}
                            <div className="flex-1 overflow-y-auto">
                                <nav className="space-y-1 p-6">
                                    {Array(0).map((item) => (
                                        <div key={item.name}>
                                            {item.hasMegaMenu ? (
                                                <Collapsible
                                                    open={solutionsOpen}
                                                    onOpenChange={setSolutionsOpen}
                                                >
                                                    <CollapsibleTrigger className="hover:bg-accent hover:text-accent-foreground flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-3 text-base font-medium transition-colors">
                                                        {item.name}
                                                        <ChevronDown
                                                            className={`h-4 w-4 transition-transform ${solutionsOpen ? 'rotate-180' : ''}`}
                                                        />
                                                    </CollapsibleTrigger>
                                                    <CollapsibleContent className="space-y-1 pl-4">
                                                        {Array(0).map((solution, index) =>
                                                            solution.title ? (
                                                                <div
                                                                    key={`title-${index}`}
                                                                    className="text-muted-foreground/50 mt-5 px-4 py-2 text-xs font-semibold uppercase tracking-wider"
                                                                >
                                                                    {solution.title}
                                                                </div>
                                                            ) : (
                                                                <a
                                                                    key={solution.name}
                                                                    href={solution.href}
                                                                    className="hover:bg-accent hover:text-accent-foreground flex cursor-pointer items-center rounded-lg px-4 py-2 text-sm transition-colors"
                                                                    onClick={(e) => {
                                                                        setIsOpen(false);
                                                                        if (solution.href?.startsWith('#')) {
                                                                            e.preventDefault();
                                                                            setTimeout(
                                                                                () => smoothScrollTo(solution.href),
                                                                                100,
                                                                            );
                                                                        }
                                                                    }}
                                                                >
                                                                    {solution.name}
                                                                </a>
                                                            ),
                                                        )}
                                                    </CollapsibleContent>
                                                </Collapsible>
                                            ) : (
                                                <a
                                                    href={item.href}
                                                    className="hover:bg-accent hover:text-accent-foreground flex cursor-pointer items-center rounded-lg px-4 py-3 text-base font-medium transition-colors"
                                                    onClick={(e) => {
                                                        setIsOpen(false);
                                                        if (item.href.startsWith('#')) {
                                                            e.preventDefault();
                                                            setTimeout(() => smoothScrollTo(item.href), 100);
                                                        }
                                                    }}
                                                >
                                                    {item.name}
                                                </a>
                                            )}
                                        </div>
                                    ))}
                                </nav>
                            </div>

                            {/* Footer Actions */}
                            <div className="space-y-4 border-t p-6">
                                {/* Primary Actions */}
                                <div className="space-y-3">
                                    <div className="grid grid-cols-2 gap-3">
                                        <Button
                                            variant="outline"
                                            size="lg"
                                            asChild
                                            className="cursor-pointer"
                                        >
                                            <Link href="/dashboard/account">Sign In</Link>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </SheetContent>
                </Sheet>
            </div>
        </header>
    );
}

function ListItem({ title, children, href, ...props }: React.ComponentPropsWithoutRef<'li'> & { href: string }) {
    return (
        <li {...props}>
            <NavigationMenuLink
                asChild
                className="hover:text-primary"
            >
                <Link href={href}>
                    <div className="text-sm font-medium leading-none">{title}</div>
                    <p className="text-muted-foreground text-sm leading-snug">{children}</p>
                </Link>
            </NavigationMenuLink>
        </li>
    );
}
