import 'dayjs';

declare module 'dayjs' {
    interface Dayjs {
        fmt(format: VatgerDateFormat): string;
    }
}
