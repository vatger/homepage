import dayjs, {type Dayjs} from 'dayjs';
import utc from 'dayjs/plugin/utc';

export enum VatgerDateFormat {
    DATE = 'DD.MM.YYYY',
    TIME = 'HH:mm',
    TIME_SECS = 'HH:mm:ss',
    DATETIME = 'DD.MM.YYYY HH:mm',
}

function customFormatPlugin(_: unknown, dayjsClass: typeof Dayjs) {
    dayjsClass.prototype.fmt = function (format: VatgerDateFormat) {
        return this.format(format.toString());
    };
}

dayjs.extend(utc);
dayjs.extend(customFormatPlugin);

export default dayjs;
