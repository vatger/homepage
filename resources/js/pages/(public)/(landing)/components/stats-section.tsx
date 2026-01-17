import { Card, CardContent } from '@/components/ui/card';
import dayjs, { VatgerDateFormat } from '@/lib/time/dayjs';
import { usePage } from '@inertiajs/react';
import { Plane, Radar, TowerControl } from 'lucide-react';
import { Counter } from '../vatger_components/number-counter';

export function StatsSection() {
    const { len_controllers, len_controllers_ger, len_pilots, len_pilots_ger, last_update } = usePage()
        .props as unknown as {
        len_controllers: number | null;
        len_controllers_ger: number | null;
        len_pilots: number | null;
        len_pilots_ger: number | null;
        last_update: string | null;
    };

    if (
        len_controllers === null ||
        len_controllers_ger === null ||
        len_pilots === null ||
        len_pilots_ger === null ||
        last_update === null
    ) {
        return (
            <section className="relative pb-7 pt-12 sm:pb-10 sm:pt-16">
                <div className="container relative mx-auto px-4 text-center sm:px-6 lg:px-8">
                    <p className="text-muted-foreground">Loading statistics...</p>
                </div>
            </section>
        );
    }

    const stats = [
        {
            id: 1,
            icon: Radar,
            value: len_controllers_ger,
            label: 'Controllers',
            description: 'controlling the german airspace',
        },
        {
            id: 2,
            icon: TowerControl,
            value: len_controllers,
            label: 'Controllers',
            description: 'on VATSIM',
        },
        {
            id: 3,
            icon: Plane,
            value: len_pilots_ger,
            label: 'Pilots',
            description: 'flying over Germany',
        },
        {
            id: 4,
            icon: Plane,
            value: len_pilots,
            label: 'Pilots',
            description: 'on VATSIM',
        },
    ];

    return (
        <section className="relative pb-7 pt-12 sm:pb-10 sm:pt-16">
            <div className="container relative mx-auto px-4 sm:px-6 lg:px-8">
                <div className="mx-auto mb-10 max-w-2xl text-center">
                    <h2 className="mb-4 text-3xl font-bold tracking-tight sm:text-4xl">Meet The Community</h2>
                    <p className="text-muted-foreground text-lg">
                        Live statistics from the VATSIM network. To view more click{' '}
                        <a
                            href="https://map.vatsim.net"
                            className="text-accent-500 hover:underline"
                            target="_blank"
                            rel="noopener"
                        >
                            here
                        </a>
                        .
                    </p>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-2 gap-6 md:gap-8 lg:grid-cols-4">
                    {stats.map((stat) => (
                        <Card
                            key={stat.id}
                            className="bg-muted py-0 text-center"
                        >
                            <CardContent className="p-6">
                                <div className="mb-4 flex justify-center">
                                    <div className="border-secondary-200 dark:border-secondary-700 rounded-xl border p-3">
                                        <stat.icon className="text-accent-500 h-6 w-6" />
                                    </div>
                                </div>
                                <div className="space-y-1">
                                    <h3 className="text-foreground text-2xl font-bold sm:text-3xl">
                                        <Counter
                                            initial={Math.floor(stat.value * 0.5)}
                                            target={stat.value}
                                            duration={1000}
                                        />
                                    </h3>
                                    <p className="text-foreground font-semibold">{stat.label}</p>
                                    <p className="text-muted-foreground text-sm">{stat.description}</p>
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <p className="text-secondary-200 dark:text-secondary-700 mt-7 text-center text-xs sm:mt-10">
                    Last updated: {dayjs.utc(last_update).fmt(VatgerDateFormat.TIME)} UTC
                </p>
            </div>
        </section>
    );
}
