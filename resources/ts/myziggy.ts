import { Ziggy } from "@/js/ziggy_autogen";
// @ts-ignore
import route from "~vendor/tightenco/ziggy/dist/index.m.js";
import { Config } from "ziggy-js";

export const zroute = function (
  name: string,
  params: any,
  absolute?: boolean,
  config?: Config,
): string {
  return route(name, params, absolute, Ziggy);
};
