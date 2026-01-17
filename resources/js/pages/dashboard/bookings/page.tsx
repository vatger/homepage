'use client';
import { useState, useMemo } from 'react';
import { useSearchParams, useRouter } from 'next/navigation';

import { GroupSelector } from './_components/GroupSelector';
import { DayOverview } from './_components/DayOverview';
import { DayDetail } from './_components/DayDetail';
import { BookingDatePicker } from './_components/BookingDatePicker';
import { BookingDialog } from './_components/BookingDialog';
import {
    airportGroups,
    generateDayBookings,
    generateStationBookings,
} from './_data/mockData';

const Index = () => {
    const searchParams = useSearchParams();
    const router = useRouter();

    // State from URL or defaults
    const selectedGroupId = searchParams.get('group') || airportGroups[0].id;
    const selectedDateStr = searchParams.get('date');
    const selectedDate = selectedDateStr ? new Date(selectedDateStr) : null;

    // Booking dialog state
    const [bookingDialog, setBookingDialog] = useState<{
        open: boolean;
        stationName: string;
        stationId: string;
        slotId: string;
        timeSlot: string;
    }>({
        open: false,
        stationName: '',
        stationId: '',
        slotId: '',
        timeSlot: '',
    });

    // Get current group
    const currentGroup =
        airportGroups.find((g) => g.id === selectedGroupId) || airportGroups[0];

    // Generate bookings for current group
    const dayBookings = useMemo(
        () => generateDayBookings(selectedGroupId),
        [selectedGroupId],
    );

    const stationBookings = useMemo(() => {
        if (!selectedDate) return [];
        return generateStationBookings(currentGroup, selectedDate);
    }, [currentGroup, selectedDate]);

    function updateSearchParams(updater: (params: URLSearchParams) => void) {
        const params = new URLSearchParams(searchParams.toString());
        updater(params);
        router.push(`?${params.toString()}`);
    }

    // Handlers
    const handleSelectGroup = (groupId: string) => {
        updateSearchParams((params) => {
            params.set('group', groupId);
            params.delete('date');
        });
    };

    const handleSelectDate = (date: Date | undefined) => {
        if (!date) return;

        updateSearchParams((params) => {
            params.set('date', date.toISOString().slice(0, 10)); // YYYY-MM-DD
        });
    };

    const handleBack = () => {
        updateSearchParams((params) => {
            params.delete('date');
        });
    };

    const handleBookSlot = (stationId: string, slotId: string) => {
        const station = currentGroup.stations.find((s) => s.id === stationId);
        const booking = stationBookings.find((b) => b.station.id === stationId);
        const slot = booking?.timeSlots.find((s) => s.id === slotId);

        if (station && slot) {
            setBookingDialog({
                open: true,
                stationName: station.callsign,
                stationId,
                slotId,
                timeSlot: `${slot.startTime}–${slot.endTime}`,
            });
        }
    };

    const handleConfirmBooking = (duration: string) => {
        // toast({
        //     title: 'Buchung erfolgreich!',
        //     description: `${bookingDialog.stationName} wurde für ${duration} Stunde(n) gebucht.`,
        // });
    };

    return (
        <div className="min-h-screen bg-background">
            <main className="py-6 space-y-6">
                {/* Group Selection */}
                <section className="space-y-3">
                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <GroupSelector
                            groups={airportGroups}
                            selectedGroupId={selectedGroupId}
                            onSelectGroup={handleSelectGroup}
                        />
                        <BookingDatePicker
                            date={selectedDate || undefined}
                            onDateChange={handleSelectDate}
                        />
                    </div>
                </section>

                {/* Content */}
                {selectedDate ? (
                    <DayDetail
                        group={currentGroup}
                        date={selectedDate}
                        bookings={stationBookings}
                        onBack={handleBack}
                        onBookSlot={handleBookSlot}
                    />
                ) : (
                    <section className="space-y-4">
                        <div className="flex items-center justify-between">
                            <h2 className="text-xl font-semibold text-foreground">
                                14-Days-Overview
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                {currentGroup.icao} – {currentGroup.name}
                            </p>
                        </div>
                        <DayOverview
                            bookings={dayBookings}
                            selectedDate={selectedDate}
                            onSelectDate={handleSelectDate}
                        />
                    </section>
                )}
            </main>

            {/* Booking Dialog */}
            <BookingDialog
                open={bookingDialog.open}
                onOpenChange={(open) =>
                    setBookingDialog((prev) => ({ ...prev, open }))
                }
                stationName={bookingDialog.stationName}
                timeSlot={bookingDialog.timeSlot}
                onConfirm={handleConfirmBooking}
            />
        </div>
    );
};

export default Index;
