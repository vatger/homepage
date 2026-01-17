import { AirportGroup, StationBooking } from '../_types/booking';
import { StationBookingRow } from './StationBookingRow';
import { format } from 'date-fns';
import { de, enGB } from 'date-fns/locale';
import { ArrowLeft, Calendar, MapPin } from 'lucide-react';
import { Button } from '@/components/ui/button';

interface DayDetailProps {
    group: AirportGroup;
    date: Date;
    bookings: StationBooking[];
    onBack: () => void;
    onBookSlot: (stationId: string, slotId: string) => void;
}

export function DayDetail({
    group,
    date,
    bookings,
    onBack,
    onBookSlot,
}: DayDetailProps) {
    const formattedDate = format(date, 'EEEE, dd. MMMM yyyy', { locale: enGB });

    return (
        <div className="space-y-6 animate-fade-in">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div className="flex items-center gap-4">
                    <Button
                        variant="ghost"
                        size="icon"
                        onClick={onBack}
                        className="shrink-0"
                    >
                        <ArrowLeft className="w-5 h-5" />
                    </Button>
                    <div>
                        <div className="flex items-center gap-2 text-muted-foreground text-sm mb-1">
                            <Calendar className="w-4 h-4" />
                            <span>{formattedDate}</span>
                        </div>
                        <h2 className="text-2xl font-bold text-foreground flex items-center gap-2">
                            <MapPin className="w-5 h-5 text-primary" />
                            {group.icao} – {group.name}
                        </h2>
                    </div>
                </div>
            </div>

            <div className="space-y-4">
                {bookings.map((booking, index) => (
                    <div
                        key={booking.station.id}
                        style={{ animationDelay: `${index * 50}ms` }}
                    >
                        <StationBookingRow
                            booking={booking}
                            onBookSlot={onBookSlot}
                        />
                    </div>
                ))}
            </div>
        </div>
    );
}
