import Typewriter from "typewriter-effect/dist/core";
import _ from "lodash";
import { getLanguage } from "@/ts/template";

let slogans_de = [
  "Deutschlands Himmel, Virtuell Perfekt!",
  "Deutschland Fliegt Digital. Steig Ein!",
  "Mit Leidenschaft über den Wolken: VATSIM Germany.",
  "Der Himmel ist Grenzenlos. Unser Service Auch!",
  "Deutschland Online, Deutschland Über den Wolken.",
  "Hoch Hinaus mit VATSIM Germany!",
  "Virtueller Himmel, Echte Begeisterung.",
  "Im Digitalen Himmel Deutschlands Zu Hause.",
  "Gemeinsam Fliegen, Virtuell Erleben.",
  "Über Deutschland, Unter Kontrolle: VATSIM Germany.",
  "Virtueller Himmel, echte Leidenschaft!",
  "VATSIM Germany: Wo Träume fliegen lernen.",
  "Durch Deutschland fliegen, virtuell verbunden.",
  "Deutschlands virtuelle Lüfte, authentisch gesteuert.",
  "Fliegen Sie mit uns in Deutschlands virtuellem Himmel!",
  "Echtzeit, Echtpassion, Virtueller Himmel.",
  "VATSIM Germany: Wo der virtuelle Flug real wird.",
  "Hoch über Deutschland – virtuell und leidenschaftlich.",
  "Für die Liebe zum Flug – VATSIM Germany.",
  "Deutschlands digitale Flügel, real gesteuert.",
];

let slogans_en = [
  "Germany's Sky, Virtually Perfect!",
  "Germany Flies Digital. Get Onboard!",
  "With Passion above the Clouds: VATSIM Germany.",
  "The Sky Knows No Bounds. Our Service Doesn't Either!",
  "Online in Germany, Above the Clouds.",
  "Soar High with VATSIM Germany!",
  "Virtual Sky, Genuine Enthusiasm.",
  "At Home in Germany's Digital Sky.",
  "Fly Together, Experience Virtually.",
  "Above Germany, Under Control: VATSIM.",
  "Virtual Sky, Real Passion!",
  "VATSIM Germany: Where Dreams Learn to Fly.",
  "Fly Through Germany, Virtually Connected.",
  "Germany's Virtual Skies, Authentically Controlled.",
  "Fly with Us in Germany's Virtual Sky!",
  "Real-time, Real Passion, Virtual Sky.",
  "VATSIM Germany: Where Virtual Flight Becomes Real.",
  "High Above Germany – Virtually and Passionately.",
  "For the Love of Flight – VATSIM Germany.",
  "Germany's Digital Wings, Actively Controlled.",
  "Take Off into a Virtual World of Realistic Flight Experience – Welcome to VATSIM Germany!",
  "Fly, Learn, and Grow Together – VATSIM Germany Connects Pilots and Controllers!",
  "Your Passion for Flying Meets Genuine Teamwork – Become Part of VATSIM Germany!",
  "Don't Fly Alone – VATSIM Germany Offers You an Active Community of Flight Enthusiasts!",
  "Experience the Thrill of Virtual Flying in Real Time.",
];

let slogans = _.shuffle(getLanguage() == "en" ? slogans_en : slogans_de);

new Typewriter("#typewriter", {
  strings: slogans,
  autoStart: true,
  delay: 80,
  deleteSpeed: 20,
  loop: true,
});
