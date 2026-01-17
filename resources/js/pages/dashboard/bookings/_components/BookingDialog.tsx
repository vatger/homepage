import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useState } from 'react';

interface BookingDialogProps {
    open: boolean;
    onOpenChange: (open: boolean) => void;
    stationName: string;
    timeSlot: string;
    onConfirm: (duration: string) => void;
}

export function BookingDialog({
    open,
    onOpenChange,
    stationName,
    timeSlot,
    onConfirm,
}: BookingDialogProps) {
    const [duration, setDuration] = useState('2');

    const handleConfirm = () => {
        onConfirm(duration);
        onOpenChange(false);
    };

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="sm:max-w-106.25">
                <DialogHeader>
                    <DialogTitle>Buchung bestätigen</DialogTitle>
                    <DialogDescription>
                        Du buchst{' '}
                        <span className="font-semibold text-foreground">
                            {stationName}
                        </span>{' '}
                        im Zeitfenster{' '}
                        <span className="font-mono text-foreground">
                            {timeSlot}
                        </span>
                        .
                    </DialogDescription>
                </DialogHeader>
                <div className="grid gap-4 py-4">
                    <div className="grid grid-cols-4 items-center gap-4">
                        <Label htmlFor="duration" className="text-right">
                            Dauer (Std.)
                        </Label>
                        <Input
                            id="duration"
                            type="number"
                            min="1"
                            max="4"
                            value={duration}
                            onChange={(e) => setDuration(e.target.value)}
                            className="col-span-3"
                        />
                    </div>
                </div>
                <DialogFooter>
                    <Button
                        variant="outline"
                        onClick={() => onOpenChange(false)}
                    >
                        Abbrechen
                    </Button>
                    <Button onClick={handleConfirm}>Buchung bestätigen</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
