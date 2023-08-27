import Typewriter from 'typewriter-effect/dist/core';
import _ from 'lodash';

let slogans = [
    'Deutschlands Himmel, Virtuell Perfekt!',
    'Deutschland Fliegt Digital. Steig Ein!',
    'Mit Leidenschaft über den Wolken: VATSIM Germany.',
    'Der Himmel ist Grenzenlos. Unser Service Auch!',
    'Deutschland Online, Deutschland Über den Wolken.',
    'Hoch Hinaus mit VATSIM Deutschland!',
    'Virtueller Himmel, Echte Begeisterung.',
    'Im Digitalen Himmel Deutschlands Zu Hause.',
    'Gemeinsam Fliegen, Virtuell Erleben.',
    'Über Deutschland, Unter Kontrolle: VATSIM.',
    'Virtueller Himmel, echte Leidenschaft!',
    'VATSIM Germany: Wo Träume fliegen lernen.',
    'Durch Deutschland fliegen, virtuell verbunden.',
    'Deutschlands virtuelle Lüfte, authentisch gesteuert.',
    'Fliegen Sie mit uns in Deutschlands virtuellem Himmel!',
    'Echtzeit, Echtpassion, Virtueller Himmel.',
    'VATSIM Germany: Wo der virtuelle Flug real wird.',
    'Hoch über Deutschland – virtuell und leidenschaftlich.',
    'Für die Liebe zum Flug – VATSIM Germany.',
    'Deutschlands digitale Flügel, real gesteuert.',
];

slogans = _.shuffle(slogans);

new Typewriter('#typewriter', {
    strings: slogans,
    autoStart: true,
    delay: 80,
    deleteSpeed: 20,
});
