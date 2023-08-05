import { Ziggy } from '@/js/ziggy_autogen';
// @ts-ignore
import route from '~vendor/tightenco/ziggy/dist/index.m.js';
import { Config, RouteParam, RouteParamsWithQueryOverload } from 'ziggy-js';

export const zroute = function (name: string, params?: RouteParamsWithQueryOverload | RouteParam, absolute?: boolean, config?: Config): string {
    return route(name, params, absolute, Ziggy);
};
