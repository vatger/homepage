'use client';

import { Monitor, Moon, Sun } from 'lucide-react';
import { useTheme } from 'next-themes';

import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

export function ModeToggle() {
    const { setTheme } = useTheme();

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="outline" size="icon">
                    <Sun className="block dark:hidden" />
                    <Moon className="hidden dark:block" />
                    <span className="sr-only">Toggle theme</span>
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent className="w-auto min-w-0 p-1 space-y-1 rounded-lg">
                <DropdownMenuItem onClick={() => setTheme('light')}>
                    <Sun />
                </DropdownMenuItem>

                <DropdownMenuItem onClick={() => setTheme('dark')}>
                    <Moon />
                </DropdownMenuItem>

                <DropdownMenuItem onClick={() => setTheme('system')}>
                    <Monitor />
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
