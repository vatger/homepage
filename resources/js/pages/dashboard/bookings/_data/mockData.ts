import {
    AirportGroup,
    DayBooking,
    StationBooking,
    DayStatus,
    StationStatus,
} from '../_types/booking';
import { addDays, format } from 'date-fns';

export const airportGroups: AirportGroup[] = [
    {
        id: 'edgg',
        name: 'Langen',
        icao: 'EDGG',
        stations: [
            {
                id: 'edgg_pah_ctr',
                name: 'Langen Radar',
                callsign: 'EDGG_PAH_CTR',
                frequency: '123.456',
            },
            {
                id: 'edgg_gin_ctr',
                name: 'Langen Radar',
                callsign: 'EDGG_GIN_CTR',
                frequency: '123.456',
            },
            {
                id: 'edgg_dkb_ctr',
                name: 'Langen Radar',
                callsign: 'EDGG_DKB_CTR',
                frequency: '123.456',
            },
            {
                id: 'edgg_ktg_ctr',
                name: 'Langen Radar',
                callsign: 'EDGG_KTG_CTR',
                frequency: '123.456',
            },
        ],
    },
    {
        id: 'eddf',
        name: 'Frankfurt',
        icao: 'EDDF',
        stations: [
            {
                id: 'eddf_app',
                name: 'Approach',
                callsign: 'EDDF_APP',
                frequency: '120.805',
            },
            {
                id: 'eddf_twr',
                name: 'Tower',
                callsign: 'EDDF_TWR',
                frequency: '119.905',
            },

            {
                id: 'eddf_gnd',
                name: 'Ground',
                callsign: 'EDDF_GND',
                frequency: '121.855',
            },
            {
                id: 'eddf_del',
                name: 'Delivery',
                callsign: 'EDDF_DEL',
                frequency: '121.905',
            },
        ],
    },
    {
        id: 'eddm',
        name: 'München',
        icao: 'EDDM',
        stations: [
            {
                id: 'eddm_del',
                name: 'Delivery',
                callsign: 'EDDM_DEL',
                frequency: '121.755',
            },
            {
                id: 'eddm_gnd',
                name: 'Ground',
                callsign: 'EDDM_GND',
                frequency: '121.725',
            },
            {
                id: 'eddm_twr',
                name: 'Tower',
                callsign: 'EDDM_TWR',
                frequency: '118.705',
            },
        ],
    },
    {
        id: 'eddb',
        name: 'Berlin',
        icao: 'EDDB',
        stations: [
            {
                id: 'eddb_del',
                name: 'Delivery',
                callsign: 'EDDB_DEL',
                frequency: '121.605',
            },
            {
                id: 'eddb_gnd',
                name: 'Ground',
                callsign: 'EDDB_GND',
                frequency: '121.755',
            },
            {
                id: 'eddb_twr',
                name: 'Tower',
                callsign: 'EDDB_TWR',
                frequency: '119.505',
            },
        ],
    },
    {
        id: 'eddl',
        name: 'Düsseldorf',
        icao: 'EDDL',
        stations: [
            {
                id: 'eddl_del',
                name: 'Delivery',
                callsign: 'EDDL_DEL',
                frequency: '121.905',
            },
            {
                id: 'eddl_gnd',
                name: 'Ground',
                callsign: 'EDDL_GND',
                frequency: '121.905',
            },
            {
                id: 'eddl_twr',
                name: 'Tower',
                callsign: 'EDDL_TWR',
                frequency: '118.305',
            },
        ],
    },
];

function getRandomStatus(): StationStatus {
    const rand = Math.random();
    if (rand < 0.4) return 'free';
    if (rand < 0.75) return 'partial';
    return 'full';
}

function getRandomController(): string {
    const controllers = [
        'Max M.',
        'Lisa S.',
        'Tom K.',
        'Julia W.',
        'Felix B.',
        'Anna R.',
    ];
    return controllers[Math.floor(Math.random() * controllers.length)];
}

export function generateDayBookings(groupId: string): DayBooking[] {
    const today = new Date();
    const bookings: DayBooking[] = [];

    for (let i = 0; i < 14; i++) {
        const date = addDays(today, i);
        const isWeekend = date.getDay() === 0 || date.getDay() === 6;
        const isEventDay = Math.random() < 0.1;

        let bookablePositions: number;
        let status: DayStatus;
        let summary: string;

        if (isEventDay) {
            bookablePositions = 0;
            status = 'red';
            summary = 'Event';
        } else if (isWeekend) {
            bookablePositions = Math.floor(Math.random() * 2);
            status =
                bookablePositions >= 2
                    ? 'green'
                    : bookablePositions === 1
                      ? 'yellow'
                      : 'red';
            summary =
                bookablePositions === 0
                    ? 'Fully booked'
                    : `${bookablePositions} Position(s) free`;
        } else {
            bookablePositions = Math.floor(Math.random() * 4) + 1;
            status =
                bookablePositions >= 2
                    ? 'green'
                    : bookablePositions === 1
                      ? 'yellow'
                      : 'red';
            summary = `${bookablePositions} Position(s) free`;
        }

        bookings.push({
            date,
            status,
            summary,
            bookablePositions,
            isEventDay,
        });
    }

    return bookings;
}

export function generateStationBookings(
    group: AirportGroup,
    date: Date,
): StationBooking[] {
    return group.stations.map((station) => ({
        station,
        timeSlots: [
            {
                id: '1',
                startTime: '00:00',
                endTime: '08:00',
                status: getRandomStatus(),
                bookedBy:
                    Math.random() > 0.5 ? [getRandomController()] : undefined,
            },
            {
                id: '2',
                startTime: '08:00',
                endTime: '12:00',
                status: getRandomStatus(),
                bookedBy:
                    Math.random() > 0.5 ? [getRandomController()] : undefined,
            },
            {
                id: '3',
                startTime: '12:00',
                endTime: '16:00',
                status: getRandomStatus(),
                bookedBy:
                    Math.random() > 0.5 ? [getRandomController()] : undefined,
            },
            {
                id: '4',
                startTime: '16:00',
                endTime: '20:00',
                status: getRandomStatus(),
                bookedBy:
                    Math.random() > 0.5 ? [getRandomController()] : undefined,
            },
            {
                id: '5',
                startTime: '20:00',
                endTime: '24:00',
                status: getRandomStatus(),
                bookedBy:
                    Math.random() > 0.5 ? [getRandomController()] : undefined,
            },
        ],
    }));
}
