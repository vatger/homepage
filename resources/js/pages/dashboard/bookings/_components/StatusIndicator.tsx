import { cn } from '@/lib/utils';
import { DayStatus } from '../_types/booking';

interface StatusIndicatorProps {
    status: DayStatus;
    size?: 'sm' | 'md' | 'lg';
    className?: string;
}

export function StatusIndicator({
    status,
    size = 'md',
    className,
}: StatusIndicatorProps) {
    const sizeClasses = {
        sm: 'w-2 h-2',
        md: 'w-3 h-3',
        lg: 'w-4 h-4',
    };

    return (
        <div
            className={cn(
                'w-3 h-3 rounded-full',
                sizeClasses[size],
                status === 'green' &&
                    'bg-success-700 shadow-sm shadow-success-700',
                status === 'yellow' &&
                    'bg-warning-700 shadow-sm shadow-warning-700',
                status === 'red' && 'bg-danger-700 shadow-sm shadow-danger-700',
                className,
            )}
        />
    );
}
