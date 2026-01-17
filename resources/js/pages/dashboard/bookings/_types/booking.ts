export type StationStatus = 'free' | 'partial' | 'full';
export type DayStatus = 'green' | 'yellow' | 'red';

export interface Station {
    id: string;
    name: string;
    callsign: string;
    frequency: string;
}

export interface TimeSlot {
    id: string;
    startTime: string;
    endTime: string;
    status: StationStatus;
    bookedBy?: string[];
}

export interface StationBooking {
    station: Station;
    timeSlots: TimeSlot[];
}

export interface DayBooking {
    date: Date;
    status: DayStatus;
    summary: string;
    bookablePositions: number;
    isEventDay: boolean;
}

export interface AirportGroup {
    id: string;
    name: string;
    icao: string;
    stations: Station[];
}

export const TIME_SLOTS = [
    { id: '1', startTime: '08:00', endTime: '12:00' },
    { id: '2', startTime: '12:00', endTime: '16:00' },
    { id: '3', startTime: '16:00', endTime: '20:00' },
    { id: '4', startTime: '20:00', endTime: '24:00' },
] as const;
