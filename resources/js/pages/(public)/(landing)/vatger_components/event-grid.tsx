/** biome-ignore-all lint/performance/noImgElement: <explanation> */
'use client';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import dayjs, { VatgerDateFormat } from '@/lib/time/dayjs';
import { Link } from '@inertiajs/react';
import { useState } from 'react';
import type { VatsimEvent } from './upcoming-events';

export function BlogGrid({ blogs }: { blogs: VatsimEvent[] }) {
    const [blogCount, setBlogCount] = useState(4);

    return (
        <>
            {/* Blog Grid */}
            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
                {blogs.slice(0, blogCount).map((blog) => {
                    const startDate = dayjs.utc(blog.start_time);

                    return (
                        <Link
                            key={blog.id}
                            href={`/event/${blog.id}`}
                            className="block h-full"
                        >
                            <Card className="h-full max-w-96 overflow-hidden py-0 transition-all hover:-translate-y-1 hover:shadow-lg max-sm:mx-auto sm:max-w-none">
                                <CardContent className="flex h-full flex-col px-0">
                                    {/* top: fixed-height image */}
                                    <div className="aspect-video">
                                        <img
                                            src={blog.banner}
                                            alt={blog.name}
                                            className="size-full object-cover dark:brightness-[0.95]"
                                            loading="lazy"
                                        />
                                    </div>

                                    {/* bottom: grows to fill remaining card height */}
                                    <div className="flex flex-1 flex-col gap-3 p-6">
                                        <p className="text-muted-foreground text-xs uppercase tracking-widest">
                                            {blog.type}
                                        </p>

                                        <div className="flex flex-wrap gap-2">
                                            {blog.airports.map((apt) => (
                                                <Badge
                                                    key={apt.icao}
                                                    variant="secondary"
                                                >
                                                    {apt.icao}
                                                </Badge>
                                            ))}
                                        </div>

                                        <h3 className="hover:text-primary text-xl font-bold transition-colors">
                                            {blog.name}
                                        </h3>

                                        {/* pushed to bottom of the text area */}
                                        <p
                                            className="mt-auto"
                                            suppressHydrationWarning
                                        >
                                            {startDate.fmt(VatgerDateFormat.DATETIME)}
                                        </p>
                                    </div>
                                </CardContent>
                            </Card>
                        </Link>
                    );
                })}
            </div>

            <div className="mt-16 flex justify-center">
                <Button onClick={() => setBlogCount(blogCount + 4)}>View More</Button>
            </div>
        </>
    );
}
