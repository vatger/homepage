import { DayBooking } from '../_types/booking';
import { DayTile } from './DayTile';

interface DayOverviewProps {
    bookings: DayBooking[];
    selectedDate: Date | null;
    onSelectDate: (date: Date) => void;
}

export function DayOverview({
    bookings,
    selectedDate,
    onSelectDate,
}: DayOverviewProps) {
    return (
        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
            {bookings.map((booking, index) => (
                <div
                    key={booking.date.toISOString()}
                    className="animate-in fade-in slide-in-from-bottom-2"
                    style={{ animationDelay: `${index * 30}ms` }}
                >
                    <DayTile
                        booking={booking}
                        isSelected={
                            selectedDate?.toDateString() ===
                            booking.date.toDateString()
                        }
                        onClick={() => onSelectDate(booking.date)}
                    />
                </div>
            ))}
        </div>
    );
}
