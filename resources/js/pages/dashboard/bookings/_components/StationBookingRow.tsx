import { StationBooking } from '../_types/booking';
import { TimeSlotCell } from './TimeSlotCell';
import { Radio } from 'lucide-react';

interface StationBookingRowProps {
    booking: StationBooking;
    onBookSlot: (stationId: string, slotId: string) => void;
}

export function StationBookingRow({
    booking,
    onBookSlot,
}: StationBookingRowProps) {
    return (
        <div className="p-4 animate-slide-in">
            <div className="flex items-center gap-3 mb-4 pb-3 border-b border-border">
                <div className="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10 text-primary">
                    <Radio className="w-5 h-5" />
                </div>
                <div>
                    <h3 className="font-semibold text-foreground">
                        {booking.station.callsign}
                    </h3>
                    <div className="flex items-center gap-2 text-xs text-muted-foreground">
                        <span>{booking.station.name}</span>
                        <span className="font-mono">
                            {booking.station.frequency}
                        </span>
                    </div>
                </div>
            </div>

            <div className="grid grid-cols-2 md:grid-cols-5 gap-2">
                {booking.timeSlots.map((slot) => (
                    <TimeSlotCell
                        key={slot.id}
                        slot={slot}
                        onBook={() => onBookSlot(booking.station.id, slot.id)}
                    />
                ))}
            </div>
        </div>
    );
}
