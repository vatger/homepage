import {
    ArrowRight,
    Calendar,
    Clock,
    ExternalLink,
    Route,
    Spotlight,
} from 'lucide-react';
import Link from 'next/link';
import PageHeader from '@/app/(public)/(content)/_components/page-header';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import dayjs, { VatgerDateFormat } from '@/lib/time/dayjs';

const REVALIDATE_EVENT_SECS = 60 * 60 * 5; // 5 Hour

type VatsimEventDetailRoute = {
    departure: string;
    arrival: string;
    route: string;
};

type VatsimEventDetails = {
    data: {
        id: number;
        type: 'Event';
        name: string;
        link: string;
        organisers: Array<{
            region: string;
            division: string;
            subdivision: string | null;
            organised_by_vatsim: boolean;
        }>;
        airports: Array<{
            icao: string;
        }>;
        routes: Array<VatsimEventDetailRoute>;
        start_time: string; // ISO 8601 UTC timestamp
        end_time: string; // ISO 8601 UTC timestamp
        short_description: string;
        description: string;
        banner: string;
    };
};

function RouteView({ routes }: { routes: Array<VatsimEventDetailRoute> }) {
    if (!routes || routes.length === 0) return null;

    return (
        <div className="col-span-full flex items-start gap-3">
            <Route className="mt-0.5 h-5 w-5 text-muted-foreground" />
            <div className={'grow'}>
                <p className="text-sm font-medium mb-2">Suggested Routes</p>

                <div className="space-y-3">
                    {routes.map((route) => (
                        <Card
                            key={`${route.departure}-${route.arrival}`}
                            className="gap-2 p-2 bg-secondary-50 shadow-none dark:bg-secondary-700 border-transparent"
                        >
                            <CardHeader
                                className={
                                    'flex gap-1 text-xs font-semibold items-center pl-1'
                                }
                            >
                                <p>{route.departure}</p>
                                <ArrowRight size={16} strokeWidth={2} />
                                <p>{route.arrival}</p>
                            </CardHeader>
                            <CardContent className={'pl-1'}>
                                <p className="font-mono text-sm wrap-break-word">
                                    {route.route}
                                </p>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            </div>
        </div>
    );
}

export default async function EventPage({
    params,
}: {
    params: Promise<{ id: number }>;
}) {
    const { id } = await params;

    const eventDetails: VatsimEventDetails = await fetch(
        `https://my.vatsim.net/api/v2/events/view/${id}`,
        {
            cache: 'force-cache',
            next: {
                revalidate: REVALIDATE_EVENT_SECS,
            },
        },
    ).then((res) => res.json());

    const eventStart = dayjs.utc(eventDetails.data.start_time);
    const eventEnd = dayjs.utc(eventDetails.data.end_time);

    return (
        <>
            <PageHeader
                title={eventDetails.data.name}
                imageSrc={eventDetails.data.banner}
                description={eventDetails.data.short_description}
                badges={eventDetails.data.airports.map(
                    (airport) => airport.icao,
                )}
            />

            <Card>
                <CardHeader className="space-y-3">
                    <CardTitle className="text-xl">Event Details</CardTitle>
                    <CardDescription className="text-base leading-relaxed col-span-1">
                        {eventDetails.data.description}
                    </CardDescription>
                    <Separator />
                </CardHeader>

                <CardContent className="grid gap-6 sm:grid-cols-3">
                    <div className="flex items-start gap-3">
                        <Calendar className="mt-0.5 h-5 w-5 text-muted-foreground" />
                        <div>
                            <p className="text-sm font-medium">Date</p>
                            <p className="text-sm text-muted-foreground">
                                {eventStart.fmt(VatgerDateFormat.DATE)}
                            </p>
                        </div>
                    </div>

                    <div className="flex items-start gap-3">
                        <Clock className="mt-0.5 h-5 w-5 text-muted-foreground" />
                        <div>
                            <p className="text-sm font-medium">Time (UTC)</p>
                            <p className="text-sm text-muted-foreground">
                                {`${eventStart.fmt(VatgerDateFormat.TIME)} - ${eventEnd.fmt(VatgerDateFormat.TIME)}`}
                            </p>
                        </div>
                    </div>

                    <div className="flex items-start gap-3">
                        <Spotlight className="mt-0.5 h-5 w-5 text-muted-foreground" />
                        <div>
                            <p className="text-sm font-medium">Type</p>
                            <p className="text-sm text-muted-foreground">
                                {eventDetails.data.type}
                            </p>
                        </div>
                    </div>

                    <RouteView routes={eventDetails.data.routes} />
                </CardContent>
                <CardFooter>
                    <Link href={eventDetails.data.link} target={'_blank'}>
                        <Button variant={'secondary'} className={'mt-5'}>
                            <ExternalLink />
                            View on VATSIM
                        </Button>
                    </Link>
                </CardFooter>
            </Card>
        </>
    );
}
