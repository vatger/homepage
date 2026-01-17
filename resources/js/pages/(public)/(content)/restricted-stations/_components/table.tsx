'use client';

import { useState, useMemo } from 'react';
import {
    Search,
    ChevronUp,
    ChevronDown,
    ChevronsUpDown,
    ChevronLeft,
    ChevronRight,
} from 'lucide-react';

import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

type SortDirection = 'asc' | 'desc' | null;

const ITEMS_PER_PAGE = 5;

export function SearchableTable<T extends Record<string, any>>({
    data,
    columns,
    rowKey,
}: {
    data: T[];
    columns: {
        key: keyof T;
        label: string;
        render?: (value: T[keyof T], row: T) => React.ReactNode;
        sortable?: boolean;
    }[];
    rowKey: (row: T) => string | number;
}) {
    const [searchQuery, setSearchQuery] = useState('');
    const [sortKey, setSortKey] = useState<keyof T | null>(null);
    const [sortDirection, setSortDirection] = useState<SortDirection>(null);
    const [currentPage, setCurrentPage] = useState(1);

    const filteredData = useMemo(() => {
        let result = [...data];

        // 🔍 Filter
        if (searchQuery.trim()) {
            const query = searchQuery.toLowerCase();
            result = result.filter((row) =>
                Object.values(row).some(
                    (value) =>
                        value != null &&
                        String(value).toLowerCase().includes(query),
                ),
            );
        }

        // ↕️ Sort
        if (sortKey && sortDirection) {
            result.sort((a, b) => {
                const aVal = String(a[sortKey] ?? '');
                const bVal = String(b[sortKey] ?? '');

                if (aVal < bVal) return sortDirection === 'asc' ? -1 : 1;
                if (aVal > bVal) return sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
        }

        return result;
    }, [data, searchQuery, sortKey, sortDirection]);

    // 📄 Pagination
    const totalPages = Math.ceil(filteredData.length / ITEMS_PER_PAGE);

    const paginatedData = useMemo(() => {
        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        return filteredData.slice(start, start + ITEMS_PER_PAGE);
    }, [filteredData, currentPage]);

    const handleSort = (key: keyof T) => {
        if (sortKey === key) {
            setSortDirection((prev) =>
                prev === 'asc' ? 'desc' : prev === 'desc' ? null : 'asc',
            );
            if (sortDirection === 'desc') setSortKey(null);
        } else {
            setSortKey(key);
            setSortDirection('asc');
        }
        setCurrentPage(1);
    };

    const SortIcon = ({ columnKey }: { columnKey: keyof T }) => {
        if (sortKey !== columnKey)
            return (
                <ChevronsUpDown className="h-4 w-4 text-muted-foreground/50" />
            );

        return sortDirection === 'asc' ? (
            <ChevronUp className="h-4 w-4 text-primary" />
        ) : (
            <ChevronDown className="h-4 w-4 text-primary" />
        );
    };

    return (
        <div className="space-y-4">
            {/* Search */}
            <div className="relative">
                <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                <Input
                    placeholder="Search..."
                    value={searchQuery}
                    onChange={(e) => {
                        setSearchQuery(e.target.value);
                        setCurrentPage(1);
                    }}
                    className="pl-10"
                />
            </div>

            {/* Table */}
            <div className="rounded-lg border overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            {columns.map((column) => (
                                <TableHead
                                    key={String(column.key)}
                                    onClick={
                                        column.sortable === false
                                            ? undefined
                                            : () => handleSort(column.key)
                                    }
                                    className={`cursor-pointer select-none ${
                                        column.sortable === false &&
                                        'cursor-default'
                                    }`}
                                >
                                    <div className="flex items-center gap-2">
                                        {column.label}
                                        {column.sortable !== false && (
                                            <SortIcon columnKey={column.key} />
                                        )}
                                    </div>
                                </TableHead>
                            ))}
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        {paginatedData.length === 0 ? (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="h-24 text-center text-muted-foreground"
                                >
                                    No results found
                                </TableCell>
                            </TableRow>
                        ) : (
                            paginatedData.map((row) => (
                                <TableRow key={rowKey(row)}>
                                    {columns.map((column) => (
                                        <TableCell key={String(column.key)}>
                                            {column.render
                                                ? column.render(
                                                      row[column.key],
                                                      row,
                                                  )
                                                : String(row[column.key] ?? '')}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        )}
                    </TableBody>
                </Table>
            </div>

            {/* Pagination */}
            <div className="flex items-center justify-between">
                <p className="text-sm text-muted-foreground">
                    Showing {(currentPage - 1) * ITEMS_PER_PAGE + 1}–
                    {Math.min(
                        currentPage * ITEMS_PER_PAGE,
                        filteredData.length,
                    )}{' '}
                    of {filteredData.length}
                </p>

                <div className="flex gap-2">
                    <Button
                        size="icon"
                        variant="outline"
                        disabled={currentPage === 1}
                        onClick={() => setCurrentPage((p) => p - 1)}
                    >
                        <ChevronLeft />
                    </Button>

                    <Button
                        size="icon"
                        variant="outline"
                        disabled={currentPage === totalPages}
                        onClick={() => setCurrentPage((p) => p + 1)}
                    >
                        <ChevronRight />
                    </Button>
                </div>
            </div>
        </div>
    );
}
