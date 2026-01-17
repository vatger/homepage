import { cn } from '@/lib/utils';
import type { DayBooking } from '../_types/booking';
import { StatusIndicator } from './StatusIndicator';
import { format, isToday, isTomorrow } from 'date-fns';
import { de, enGB } from 'date-fns/locale';
import { AlertTriangle } from 'lucide-react';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

interface DayTileProps {
    booking: DayBooking;
    isSelected: boolean;
    onClick: () => void;
}

export function DayTile({ booking, isSelected, onClick }: DayTileProps) {
    const dayName = isToday(booking.date)
        ? 'Today'
        : isTomorrow(booking.date)
          ? 'Tomorrow'
          : format(booking.date, 'EEEE', { locale: enGB });

    const dateString = format(booking.date, 'dd.MM.', { locale: de });

    return (
        <Alert
            onClick={onClick}
            className={cn(
                'w-full h-full text-left hover:cursor-pointer hover:bg-secondary-100',
                isSelected && 'day-tile-active',
            )}
            variant={
                {
                    green: 'success',
                    yellow: 'warning',
                    red: 'destructive',
                }[booking.status] as 'default' | 'destructive'
            }
        >
            <AlertTitle>
                <div className="flex items-start justify-between">
                    <div className="flex items-center gap-2">
                        <StatusIndicator status={booking.status} />
                        <span className="text-xs font-medium text-muted-foreground uppercase tracking-wide">
                            {dayName}
                        </span>
                    </div>
                    {booking.isEventDay && (
                        <AlertTriangle className="w-4 h-4 text-status-yellow" />
                    )}
                </div>
            </AlertTitle>
            <AlertDescription>
                <div className="flex flex-col gap-2">
                    <div className="text-lg font-semibold text-foreground">
                        {dateString}
                    </div>
                    <p className="text-xs text-muted-foreground">
                        {booking.summary}
                    </p>
                </div>
            </AlertDescription>
        </Alert>
    );
}
