import { ExternalLink, Heart } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import {
    FacebookIcon,
    GitHubIcon,
    InstagramIcon,
    TwitchIcon,
    XIcon,
    YouTubeIcon,
} from '@/components/vatger/brands';

type FooterLinkType = {
    [key: string]: {
        name: string;
        className?: string;
        links: Array<{
            name: string;
            href: string;
            target?: string;
        }>;
    };
};

const footerLinks: FooterLinkType = {
    vatger: {
        name: 'vatger',
        className: 'lg:col-start-5',
        links: [
            {
                name: 'Förderverein',
                href: 'https://vatger-fv.de',
                target: '_blank',
            },
            { name: 'Satzung', href: '#features' },
            { name: 'Policies', href: '#pricing' },
        ],
    },
    helpfulLinks: {
        name: 'Helpful Links',
        className: '',
        links: [
            {
                name: 'DFS Basic AIP',
                href: 'https://aip.dfs.de/basicAIP/',
                target: '_blank',
            },
        ],
    },
};

const socialLinks = [
    {
        name: 'GitHub',
        href: 'https://github.com/vatger',
        icon: GitHubIcon,
    },
    {
        name: 'Twitch',
        href: '#',
        icon: TwitchIcon,
    },
    {
        name: 'YouTube',
        href: 'https://youtube.com/u/vatger',
        icon: YouTubeIcon,
    },
    {
        name: 'Insta',
        href: '#',
        icon: InstagramIcon,
    },
    {
        name: 'X',
        href: '#',
        icon: XIcon,
    },
    {
        name: 'Facebook',
        href: '#fb',
        icon: FacebookIcon,
    },
];

export function LandingFooter() {
    return (
        <footer className="border-t bg-background">
            <div className="container mx-auto px-4 sm:px-6 lg:px-6 py-16">
                <div className="grid gap-8 grid-cols-4 lg:grid-cols-6">
                    <div className="col-span-4 lg:col-span-2 max-w-2xl">
                        <div className="flex  space-x-2 mb-4 max-lg:justify-center">
                            <a
                                href="https://shadcnstore.com"
                                target="_blank"
                                className="flex items-center space-x-2 cursor-pointer"
                                rel="noopener"
                            >
                                <span className="font-bold text-xl">
                                    vatger
                                </span>
                            </a>
                        </div>
                        <p className="text-muted-foreground mb-6 max-lg:text-center max-lg:flex max-lg:justify-center">
                            Accelerating web development with curated blocks,
                            templates, landing pages, and admin dashboards
                            designed for modern developers.
                        </p>
                        <div className="flex  space-x-4 max-lg:justify-center">
                            {socialLinks.map((social) => (
                                <Button
                                    key={social.name}
                                    variant="ghost"
                                    size="icon"
                                    asChild
                                >
                                    <a
                                        href={social.href}
                                        aria-label={social.name}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        <social.icon className="h-4 w-4 " />
                                    </a>
                                </Button>
                            ))}
                        </div>
                    </div>

                    {Object.entries(footerLinks).map(([key, value]) => {
                        return (
                            <div key={key} className={value.className}>
                                <h4 className="font-semibold mb-4">
                                    {value.name}
                                </h4>
                                <ul className="space-y-3">
                                    {value.links.map((link) => (
                                        <li key={link.name}>
                                            <a
                                                href={link.href}
                                                className="text-muted-foreground hover:text-foreground transition-colors flex gap-2 items-center hover:underline"
                                                target={link.target}
                                            >
                                                {link.name}

                                                {link.target === '_blank' ? (
                                                    <ExternalLink size={14} />
                                                ) : null}
                                            </a>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        );
                    })}
                </div>

                <Separator className="my-8" />

                <div className="flex flex-col lg:flex-row justify-between gap-2 items-start">
                    <div className="space-y-2 text-muted-foreground text-sm">
                        <div className="flex items-center gap-1">
                            <span>Made with</span>
                            <Heart className="h-4 w-4 text-accent-500 fill-current" />
                            <span>by</span>
                            <span className="font-semibold">1373921</span>&
                            <span className="font-semibold">1234027</span>
                        </div>
                        <p>© {new Date().getFullYear()} vatger</p>
                    </div>
                    <div className="flex items-center space-x-4 text-sm text-muted-foreground mt-4 md:mt-0">
                        <a
                            href="#privacy"
                            className="hover:text-foreground transition-colors"
                        >
                            Privacy Policy
                        </a>
                        <a
                            href="#terms"
                            className="hover:text-foreground transition-colors"
                        >
                            Terms of Service
                        </a>
                        <a
                            href="#cookies"
                            className="hover:text-foreground transition-colors"
                        >
                            Cookie Policy
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    );
}
