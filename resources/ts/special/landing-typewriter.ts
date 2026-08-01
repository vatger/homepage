// @ts-ignore
import Typewriter from "typewriter-effect/dist/core";
import { shuffle } from "lodash";
import { getLanguage } from "@/ts/preferences";

let slogans_de = [
  "Deutschlands Himmel, Virtuell Perfekt!",
  "Deutschland Fliegt Digital. Steig Ein!",
  "Mit Leidenschaft über den Wolken: vatger.",
  "Der Himmel ist Grenzenlos. Unser Service auch!",
  "Hoch Hinaus mit vatger!",
  "Virtueller Himmel, Echte Begeisterung.",
  "Im Digitalen Himmel Deutschlands Zu Hause.",
  "Gemeinsam Fliegen, Virtuell Erleben.",
  "Über Deutschland, Unter Kontrolle: vatger.",
  "Virtueller Himmel, echte Leidenschaft!",
  "vatger: Wo Träume fliegen lernen.",
  "Durch Deutschland fliegen, virtuell verbunden.",
  "Flieg mit mit uns in Deutschlands virtuellem Himmel!",
  "Echtzeit, Echte Passion, virtueller Himmel.",
  "vatger: Wo der virtuelle Flug real wird.",
  "Hoch über Deutschland – virtuell und leidenschaftlich.",
  "Für die Liebe zum Flug – vatger.",
  "Deutschlands digitale Flügel, real gesteuert.",
  "vatger: Mit Currywurst und Rückenwind.",
];

let slogans_en = [
  "Germany's Sky, Virtually Perfect!",
  "Germany Flies Digital. Get Onboard!",
  "With Passion above the Clouds: vatger.",
  "The Sky Knows No Bounds. Our Service Doesn't Either!",
  "Soar High with vatger!",
  "Virtual Sky, Genuine Enthusiasm.",
  "At Home in Germany's Digital Sky.",
  "Fly Together, Experience Virtually.",
  "Above Germany, Under Control: VATSIM.",
  "Virtual Sky, Real Passion!",
  "vatger: Where Dreams Learn to Fly.",
  "Fly Through Germany, Virtually Connected.",
  "Germany's Virtual Skies, Authentically Controlled.",
  "Fly with Us in Germany's Virtual Sky!",
  "Real-time, Real Passion, Virtual Sky.",
  "vatger: Where Virtual Flight Becomes Real.",
  "High Above Germany – Virtually and Passionately.",
  "For the Love of Flight – vatger.",
  "Germany's Digital Wings, Actively Controlled.",
  "Take Off into a Virtual World of Realistic Flight Experience – Welcome to vatger!",
  "Fly, Learn, and Grow Together – vatger Connects Pilots and Controllers!",
  "Your Passion for Flying Meets Genuine Teamwork – Become Part of vatger!",
  "Don't Fly Alone – vatger Offers You an Active Community of Flight Enthusiasts!",
  "Experience the Thrill of Virtual Flying in Real Time.",
  "vatger: With currywurst and tailwind.",
];

let slogans = shuffle(getLanguage() == "en" ? slogans_en : slogans_de);

new Typewriter("#typewriter", {
  strings: slogans,
  autoStart: true,
  delay: 80,
  deleteSpeed: 20,
  loop: true,
});

const slogan_one = shuffle(slogans)[0] ?? "";
const p = document.getElementById("slogan_one");
if (p instanceof HTMLParagraphElement) {
  p.textContent = slogan_one;
}
