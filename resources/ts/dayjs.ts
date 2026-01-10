import dayjs_ from 'dayjs';
import utc from 'dayjs/plugin/utc';

dayjs_.extend(utc);
export const dayjs = dayjs_;
