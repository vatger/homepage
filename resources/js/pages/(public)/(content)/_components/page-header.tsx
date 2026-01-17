/** biome-ignore-all lint/performance/noImgElement: <explanation> */
import type * as React from 'react';
import {Badge} from '@/components/ui/badge';
import {Separator} from '@/components/ui/separator';
import {cn} from '@/lib/utils';

function HeaderBadgeContainer({ badges }: { badges?: string[] }) {
    if (!badges) return null;

    return (
        <div className={'flex gap-2'}>
            {badges.map((badge) => (
                <Badge variant={'default'} key={badge}>
                    {badge}
                </Badge>
            ))}
        </div>
    );
}

export default function PageHeader({
    title,
    description,
    imageSrc,
    badges,
}: {
    title: string;
    description?: string;
    imageSrc: string;
    badges?: string[];
}) {
    return (
        <header className={cn('mb-8')}>
            {/* Structural wrapper: not a card; just a section with a subtle outline rule */}
            <div className="relative">
                <div className="relative overflow-hidden rounded-2xl">
                    <div className="pointer-events-none absolute inset-0 overflow-hidden rounded-2xl">
                        <img
                            src={imageSrc}
                            alt={'Header logo background'}
                            className={
                                'h-full w-full absolute object-cover opacity-[0.2]'
                            }
                        />
                    </div>

                    <div className="relative px-6 py-6 md:px-8 md:py-8">
                        <div
                            className={cn(
                                'rounded-xl bg-background/50 dark:bg-background/60 backdrop-blur-xs',
                                'px-5 py-5 md:px-7 md:py-6 w-fit',
                            )}
                        >
                            <div className="space-y-5">
                                <h1 className="text-2xl font-semibold tracking-tight md:text-3xl">
                                    <span className="inline-flex items-baseline gap-3">
                                        <span
                                            aria-hidden
                                            className="mt-[0.2em] h-6 w-0.75 bg-accent-500"
                                        />
                                        <span>{title}</span>
                                    </span>
                                </h1>

                                {description ? (
                                    <p className="mt-3 max-w-2xl text-sm font-medium leading-relaxed text-muted-foreground md:text-base pr-10">
                                        {description}
                                    </p>
                                ) : null}

                                <HeaderBadgeContainer badges={badges} />
                            </div>
                        </div>
                    </div>
                </div>

                <Separator className="mt-6" />
            </div>
        </header>
    );
}
