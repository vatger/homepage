'use client';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import PageHeader from '../_components/page-header';
import { SearchableTable } from './_components/table';
import { ExternalLink } from 'lucide-react';
import { RestrictedDataRow } from './_types/stations';
import { Badge } from '@/components/ui/badge';

const sampleRestrictedData: RestrictedDataRow[] = [
    {
        station: 'EDDB_A_GND',
        name: 'Berlin Apron (Main)',
        frequency: '121.855',
    },
    {
        station: 'EDDB_A_GND',
        name: 'Berlin Apron (Main)',
        frequency: '121.855',
    },
    {
        station: 'EDDB_A_GND',
        name: 'Berlin Apron (Main)',
        frequency: '121.855',
    },
    {
        station: 'EDDB_A_GND',
        name: 'Berlin Apron (Main)',
        frequency: '121.855',
    },
    {
        station: 'EDDB_A_GND',
        name: 'Berlin Apron (Main)',
        frequency: '121.855',
    },
    {
        station: 'EDDB_A_GND',
        name: 'Berlin Apron (Main)',
        frequency: '121.855',
    },
    {
        station: 'EDDB_A_GND',
        name: 'Berlin Apron (Main)',
        frequency: '121.855',
    },
];

const sampleS1Stations = [
    {
        station: 'EDLW_TWR',
        name: 'Dortmund',
        moodle: true,
    },
    {
        station: 'EDLV_TWR',
        name: 'Niederrhein',
        moodle: false,
    },
    {
        station: 'EDSB_TWR',
        name: 'Karlsruhe',
        moodle: true,
    },
];

export default function Page() {
    return (
        <>
            <PageHeader
                title="Restricted stations"
                imageSrc="/hero/hero_3.png"
            />

            <div className="grid grid-cols-4 gap-2">
                <Card className="col-span-4 lg:col-span-2">
                    <CardHeader className="font-semibold min-h-[100px] flex flex-col gap-1">
                        Restricted Stations
                        <p className="text-sm font-normal text-muted-foreground">
                            Below mentioned stations require additional
                            endorsements or training beyond your atc rating.
                        </p>
                    </CardHeader>
                    <CardContent>
                        <SearchableTable
                            data={sampleRestrictedData}
                            rowKey={(row) => row.id}
                            columns={[
                                { key: 'station', label: 'Station' },
                                { key: 'name', label: 'Name' },
                                { key: 'frequency', label: 'Frequency' },
                            ]}
                        />
                    </CardContent>
                </Card>
                {/* <Card>
                    <CardHeader className="font-semibold min-h-[100px] flex flex-col gap-1">
                        S1 Tower
                        <p className="text-sm font-normal text-muted-foreground">
                            You are authorized to staff these stations, if you
                            have an S1 from Germany. Some stations require a
                            separate{' '}
                            <a
                                href="https://https://moodle.vatsim-germany.org/"
                                className="text-accent-500 hover:underline"
                            >
                                moodle course
                            </a>
                            .
                        </p>
                    </CardHeader>
                    <CardContent>
                        <SearchableTable />
                    </CardContent>
                </Card> */}
                <Card className="col-span-4 lg:col-span-2">
                    <CardHeader className="font-semibold min-h-[100px] flex flex-col gap-1">
                        All S1 Stations
                        <p className="text-sm font-normal text-muted-foreground">
                            You are authorized to staff these stations, if you
                            have an S1 from Germany. Some stations require a
                            separate{' '}
                            <a
                                href="https://https://moodle.vatsim-germany.org/"
                                className="text-accent-500 hover:underline"
                            >
                                moodle course
                            </a>
                            .
                        </p>
                    </CardHeader>
                    <CardContent>
                        <SearchableTable
                            data={sampleS1Stations}
                            rowKey={(row) => row.station}
                            columns={[
                                { key: 'station', label: 'Station' },
                                { key: 'name', label: 'Name' },
                                {
                                    key: 'moodle',
                                    label: 'Moodle course necessary?',
                                    render: (value) =>
                                        value ? (
                                            ''
                                        ) : (
                                            <Badge variant={'destructive'}>
                                                Moodle course necessary
                                            </Badge>
                                        ),
                                    sortable: false,
                                },
                            ]}
                        />
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
