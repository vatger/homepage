'use client';

import {Calendar, CalendarDays, Clock, MapPin, Trash2} from 'lucide-react';
import type * as React from 'react';
import {Button} from '@/components/ui/button';
import {Card, CardContent, CardDescription, CardHeader, CardTitle,} from '@/components/ui/card';
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow,} from '@/components/ui/table';
import dayjs, {VatgerDateFormat} from '@/lib/time/dayjs';

type Stat = {
    label: string;
    value: string;
    hint?: string;
    icon: React.ReactNode;
    accent?: 'default' | 'muted';
};

type EventItem = {
    id: string;
    title: string;
    startsAt: string; // ISO
    endsAt?: string; // ISO
    location: string;
    role?: string;
    status: 'confirmed' | 'waitlist' | 'pending';
};

type BookingItem = {
    id: string;
    position: string;
    airport: string;
    startsAt: string; // ISO
    endsAt: string; // ISO
    owner: string;
};

function formatTimeRange(startsAtISO: string, endsAtISO?: string) {
    const starts = new Date(startsAtISO);
    const ends = endsAtISO ? new Date(endsAtISO) : undefined;

    const fmtDate = new Intl.DateTimeFormat(undefined, {
        weekday: 'short',
        month: 'short',
        day: '2-digit',
    });
    const fmtTime = new Intl.DateTimeFormat(undefined, {
        hour: '2-digit',
        minute: '2-digit',
    });

    const datePart = fmtDate.format(starts);
    const startTime = fmtTime.format(starts);
    const endTime = ends ? fmtTime.format(ends) : '';

    return ends
        ? `${datePart} · ${startTime}–${endTime}`
        : `${datePart} · ${startTime}`;
}

const demoEvents: EventItem[] = [
    {
        id: 'evt_1',
        title: 'Hamburg Overload',
        startsAt: new Date('2026-01-14T12:00:00Z').toISOString(),
        endsAt: new Date('2026-01-14T19:00:00Z').toISOString(),
        location: 'EDDH',
        role: 'Tower',
        status: 'confirmed',
    },
    {
        id: 'evt_2',
        title: "Düsseldorf In' Out",
        startsAt: new Date('2026-01-14T12:00:00Z').toISOString(),
        endsAt: new Date('2026-01-14T12:00:00Z').toISOString(),
        location: 'EDDL',
        status: 'pending',
    },
];

const demoBookings: BookingItem[] = [
    {
        id: 'b_1',
        position: 'EDDF_TWR',
        airport: 'EDDF',
        startsAt: new Date('2026-01-14T12:00:00Z').toISOString(),
        endsAt: new Date('2026-01-14T12:00:00Z').toISOString(),
        owner: '1373921',
    },
    {
        id: 'b_2',
        position: 'EDDH_GND',
        airport: 'EDDH',
        startsAt: new Date('2026-01-14T12:00:00Z').toISOString(),
        endsAt: new Date('2026-01-14T12:00:00Z').toISOString(),
        owner: '1373921',
    },
    {
        id: 'b_3',
        position: 'EDDM_APP',
        airport: 'EDDM',
        startsAt: new Date('2026-01-14T12:00:00Z').toISOString(),
        endsAt: new Date('2026-01-14T12:00:00Z').toISOString(),
        owner: '1234556',
    },
];

export default function VatgerDashboardOverview() {
    return (
        <div className="w-full">
            <div className="mx-auto space-y-5">
                <header>
                    <h1 className="text-2xl font-semibold tracking-tight">
                        Dashboard
                    </h1>
                </header>

                <ul className={'list-disc pl-4'}>
                    <li>
                        FIR Zugehörigkeit, ATC + Pilot Stunden
                        (https://api.vatsim.net/v2/members/1373921/stats)
                    </li>
                    <li>Maybe richtige Events</li>
                    <li>
                        Statistiken, online time ATC + Piloten (ggf. meiste
                        gelotste Stationen) - evtl. weiterer Bereich darunter
                    </li>
                    <li>Linksammlung</li>
                    <li>Std letzten 12 Monate</li>
                    <li>Eigene Bookings (liste)</li>
                    <li>News / Notams</li>
                    <li>Notifications</li>
                    <li>Changelog</li>
                </ul>

                <section className={'grid grid-cols-5 gap-4'}>
                    <Card>
                        <CardContent>
                            <div className="flex items-start justify-between">
                                <div>
                                    <div className="text-sm text-muted-foreground">
                                        <span>Member Since</span>
                                    </div>

                                    <div className="text-2xl mt-2 font-semibold">
                                        12.01.2025
                                    </div>
                                </div>

                                <div className="rounded-lg border border-secondary-100 p-2">
                                    <CalendarDays
                                        size={20}
                                        className="text-accent-500"
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <Card className={'p-5 gap-2'}>
                        <CardDescription>FIR Zugehörigkeit</CardDescription>
                        <CardTitle className={'text-xl font-semibold'}>
                            EDGG
                        </CardTitle>
                    </Card>
                    <Card className={'p-5 gap-2'}>
                        <CardDescription>Rating</CardDescription>
                        <CardTitle
                            className={'flex gap-2 text-xl font-semibold'}
                        >
                            <span>C1</span>
                            <span>&bull;</span>
                            <span>P0</span>
                        </CardTitle>
                    </Card>
                    <Card className={'p-5 gap-2'}>
                        <CardDescription>ATC Stunden</CardDescription>
                        <CardTitle className={'text-xl font-semibold'}>
                            1352
                        </CardTitle>
                    </Card>
                    <Card className={'p-5 gap-2'}>
                        <CardDescription>Piloten Stunden</CardDescription>
                        <CardTitle className={'text-xl font-semibold'}>
                            834
                        </CardTitle>
                    </Card>
                </section>

                {/* Bookings take prominence */}
                <section className="grid grid-cols-1 gap-4 lg:grid-cols-5">
                    <Card className="rounded-2xl lg:col-span-3">
                        <CardHeader className="pb-3">
                            <div className="flex items-start justify-between gap-2">
                                <div>
                                    <CardTitle className="text-base">
                                        Today’s bookings
                                    </CardTitle>
                                </div>
                                <div className="flex items-center gap-2">
                                    <Button size="sm">Create Booking</Button>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent className="space-y-3">
                            <Table className={'table-fixed text-center'}>
                                <TableHeader
                                    className={
                                        'bg-secondary-50 dark:bg-secondary-800'
                                    }
                                >
                                    <TableRow className={'hover:bg-inherit'}>
                                        <TableHead>Position</TableHead>
                                        <TableHead>User</TableHead>
                                        <TableHead>Date</TableHead>
                                        <TableHead>Action</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {demoBookings.map((booking) => (
                                        <TableRow key={booking.id}>
                                            <TableCell className="font-medium">
                                                {booking.position}
                                            </TableCell>
                                            <TableCell>
                                                {booking.owner}
                                            </TableCell>
                                            <TableCell>
                                                {dayjs
                                                    .utc(booking.startsAt)
                                                    .fmt(
                                                        VatgerDateFormat.DATETIME,
                                                    )}{' '}
                                                -{' '}
                                                {dayjs
                                                    .utc(booking.endsAt)
                                                    .fmt(
                                                        VatgerDateFormat.DATETIME,
                                                    )}
                                            </TableCell>
                                            <TableCell>
                                                <Button
                                                    variant={'destructive'}
                                                    size={'icon'}
                                                    disabled={
                                                        booking.owner !==
                                                        '1373921'
                                                    }
                                                >
                                                    <Trash2 />
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>

                    {/* Right column: Events (and a small timeline view) */}
                    <div className="space-y-4 col-span-2">
                        <Card className="rounded-2xl">
                            <CardHeader className="pb-3">
                                <div className="flex items-start justify-between gap-2">
                                    <div>
                                        <CardTitle className="text-base">
                                            Upcoming events
                                        </CardTitle>
                                        <CardDescription>
                                            Ausm Event-Manager
                                        </CardDescription>
                                    </div>
                                    <Button variant="outline" size="sm">
                                        Browse
                                    </Button>
                                </div>
                            </CardHeader>
                            <CardContent>
                                {demoEvents.length === 0 ? (
                                    <div className="rounded-xl border p-6 text-sm text-muted-foreground">
                                        No upcoming event signups.
                                    </div>
                                ) : (
                                    <div className="space-y-3">
                                        {demoEvents.map((e) => (
                                            <div
                                                key={e.id}
                                                className="rounded-2xl border p-4"
                                            >
                                                <div className="flex items-start justify-between gap-3">
                                                    <div className="min-w-0 space-y-1">
                                                        <div className="flex flex-wrap items-center gap-2">
                                                            <p className="truncate text-sm font-semibold tracking-tight">
                                                                {e.title}
                                                            </p>
                                                        </div>
                                                        <div className="flex flex-row items-start gap-4 text-xs text-muted-foreground">
                                                            <div className="flex gap-2">
                                                                <Calendar className="h-3.5 w-3.5" />
                                                                {dayjs
                                                                    .utc(
                                                                        e.startsAt,
                                                                    )
                                                                    .fmt(
                                                                        VatgerDateFormat.DATE,
                                                                    )}
                                                            </div>
                                                            <div
                                                                className={
                                                                    'flex gap-2'
                                                                }
                                                            >
                                                                <Clock
                                                                    size={14}
                                                                />
                                                                {dayjs
                                                                    .utc(
                                                                        e.startsAt,
                                                                    )
                                                                    .fmt(
                                                                        VatgerDateFormat.TIME,
                                                                    )}{' '}
                                                                -{' '}
                                                                {dayjs
                                                                    .utc(
                                                                        e.endsAt,
                                                                    )
                                                                    .fmt(
                                                                        VatgerDateFormat.TIME,
                                                                    )}
                                                            </div>
                                                            <div
                                                                className={
                                                                    'flex gap-2'
                                                                }
                                                            >
                                                                <MapPin className="h-3.5 w-3.5" />
                                                                {e.location}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span className="inline-flex h-2 w-2 shrink-0 rounded-full bg-[hsl(var(--color-accent-500))]" />
                                                </div>
                                                <div className="mt-3 flex items-center gap-2">
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                    >
                                                        Details
                                                    </Button>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </CardContent>
                        </Card>

                        <Card className="rounded-2xl">
                            <CardHeader className="pb-3">
                                <CardTitle className="text-base">
                                    Overview
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-3"></CardContent>
                        </Card>
                    </div>
                </section>
            </div>
        </div>
    );
}
