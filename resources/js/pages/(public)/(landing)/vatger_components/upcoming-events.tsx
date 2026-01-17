import {BlogGrid} from './event-grid';

const REVALIDATE_EVENTS_SECS = 60 * 60; // 1 Hour

export type VatsimEvent = {
    id: number;
    type: string;
    name: string;
    airports: [
        {
            icao: string;
        },
    ];
    start_time: string;
    end_time: string;
    banner: string;
};

export async function UpcomingEventsSection() {
    const events: { data: VatsimEvent[] } = await fetch(
        'https://my.vatsim.net/api/v2/events/view/division/EUD',
        {
            cache: 'force-cache',
            next: {
                revalidate: REVALIDATE_EVENTS_SECS,
            },
        },
    ).then((res) => res.json());

    const germanEvents = events.data.filter((evt) =>
        evt.airports.some((apt) => apt.icao.startsWith('ED')),
    );

    return (
        <section id="blog" className="py-16 sm:py-16">
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">
                {/* Section Header */}
                <div className="mx-auto max-w-2xl text-center mb-16">
                    <h2 className="text-3xl font-bold tracking-tight sm:text-4xl mb-4">
                        Upcoming Events
                    </h2>
                    <p className="text-lg text-muted-foreground">
                        The next upcoming events with at least one German
                        airfield participating.
                    </p>
                </div>

                <BlogGrid blogs={germanEvents} />
            </div>
        </section>
    );
}
