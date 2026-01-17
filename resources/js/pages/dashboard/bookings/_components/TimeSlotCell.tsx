import { cn } from '@/lib/utils';
import { TimeSlot, StationStatus } from '../_types/booking';
import { User, Clock } from 'lucide-react';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface TimeSlotCellProps {
    slot: TimeSlot;
    onBook?: () => void;
}

const statusLabels: Record<StationStatus, string> = {
    free: 'Free',
    partial: 'Partly Occupuied',
    full: 'Full',
};

export function TimeSlotCell({ slot, onBook }: TimeSlotCellProps) {
    const canBook = slot.status !== 'full';

    return (
        <Card
            onClick={canBook ? onBook : undefined}
            //disabled={!canBook}
            className={cn(
                'w-full text-left ',
                slot.status === 'free' &&
                    'border-success-700 hover:bg-secondary-100 hover:cursor-pointer',
                slot.status === 'partial' &&
                    'border-warning-700 hover:bg-secondary-100 hover:cursor-pointer',
                slot.status === 'full' &&
                    'border-danger-700 hover:cursor-not-allowed',
            )}
        >
            <CardHeader>
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-1.5 text-sm">
                        <Clock className="w-3 h-3" />
                        <span className="font-semibold">
                            {slot.startTime}–{slot.endTime}
                        </span>
                    </div>
                    <Badge
                        variant={'outline'}
                        className={cn(
                            'py-0.5 rounded-full text-xs uppercase tracking-wide',
                            slot.status === 'free' && 'border-success-700',
                            slot.status === 'partial' && 'border-warning-700',
                            slot.status === 'full' && 'border-danger-700',
                        )}
                    >
                        {statusLabels[slot.status]}
                    </Badge>
                </div>
            </CardHeader>
            <CardContent>
                {slot.bookedBy && slot.bookedBy.length > 0 && (
                    <div className="flex items-center gap-1.5 text-sn text-muted-foreground">
                        <User className="w-3 h-3" />
                        <span>{slot.bookedBy.join(', ')}</span>
                    </div>
                )}
            </CardContent>
        </Card>
    );
}
