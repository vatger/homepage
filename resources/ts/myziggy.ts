import { Config, route } from '~vendor/tightenco/ziggy';
// @ts-ignore
import { Ziggy } from '@/js/ziggy_autogen.js';

export const zroute = function (name: string, params: any, absolute?: boolean, config?: Config): string {
    return route(name, params, absolute, Ziggy);
};
