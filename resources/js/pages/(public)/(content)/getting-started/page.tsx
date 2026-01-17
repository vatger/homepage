'use client';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { CheckCircle2, Circle, LogIn } from 'lucide-react';
import Image from 'next/image';
import Link from 'next/link';
import * as React from 'react';
import PageHeader from '../_components/page-header';

type Step = {
    title: string;
    description: React.ReactNode;
};

const steps: Step[] = [
    {
        title: 'Register on VATSIM',
        description: (
            <div className="space-y-2">
                <p className="text-muted-foreground text-sm">
                    To become part of the German community, you need to register first on VATSIM.
                </p>
                <ul className="text-muted-foreground list-disc space-y-4 pl-5 text-sm">
                    <li>
                        If you are <b>not yet</b> a member of VATSIM, register first and create an account. <br />
                        Everything you need to know can be found{' '}
                        <a
                            className="text-accent-500 hover:underline"
                            href="https://vatsim.net/docs/about/join-vatsim"
                            target="_blank"
                            rel="noopener"
                        >
                            here
                        </a>
                    </li>
                    <li>
                        <div>
                            <p className="pb-2">
                                If you are already registered with VATSIM, use your login details to speed up the
                                process of joining vatger.
                            </p>
                            <Button variant={'outline'}>
                                <LogIn />
                                <Link href="">Login with VATSIM SSO</Link>
                            </Button>
                        </div>
                    </li>
                </ul>
            </div>
        ),
    },
    {
        title: 'EMEA/EUD/GER assignment',
        description: (
            <div className="flex flex-col space-y-2">
                <p className="text-muted-foreground text-sm">
                    To start as a controller or pilot to vACC (virtual Airtraffic Control Centre) Germany, you must
                    first go to the website of Vateud, the parent organisation of vatger and make sure, your are
                    assigned to the Germany vACC.
                </p>
                <div className="flex justify-center">
                    <Image
                        src={'/images/vateud.png'}
                        width={1920}
                        height={1080}
                        alt="Radar screen"
                        className="w-1/2 rounded-xl pb-2 shadow-lg"
                    />
                </div>

                <ol className="text-muted-foreground list-decimal space-y-1 pl-5 text-sm">
                    <li>
                        <p className="pb-2">
                            Go to{' '}
                            <a
                                className="text-accent-500 hover:underline"
                                href="https://core.vateud.net/my/profile"
                            >
                                https://core.vateud.net/my/profile
                            </a>{' '}
                            and check, if you are assigned to the German vACC.
                        </p>
                    </li>
                    <li>
                        If not, you first have to transfer to the German subdivision. You can do this by clicking on{' '}
                        <a
                            className="text-accent-500 hover:underline"
                            href="https://core.vateud.net/my/transfer"
                        >
                            Transfers
                        </a>{' '}
                        and then fill out the <b>Request Transfer</b> form.
                    </li>
                </ol>
            </div>
        ),
    },
    {
        title: 'Register/Login on vatger',
        description: (
            <div className="space-y-2">
                <p className="text-muted-foreground text-sm">
                    After successfully completing the previous steps, you now have your personal VATSIM ID and can
                    register or log in on the vatger homepage:
                </p>
                <Button variant={'outline'}>
                    <LogIn />
                    <Link href="">Login vatger homepage</Link>
                </Button>

                <p className="text-muted-foreground text-sm">
                    To complete your registration, all you have to do now is accept the rules/guidelines presented to
                    you.
                </p>
            </div>
        ),
    },
];

function StepIcon({ state }: { state: 'done' | 'current' | 'locked' }) {
    if (state === 'done') {
        return (
            <CheckCircle2
                className="h-5 w-5 text-green-600"
                aria-hidden="true"
            />
        );
    }
    if (state === 'current') {
        return (
            <Circle
                className="text-foreground h-5 w-5"
                aria-hidden="true"
            />
        );
    }
    return (
        <Circle
            className="text-muted-foreground h-5 w-5"
            aria-hidden="true"
        />
    );
}

export default function GettingStartedPage() {
    const [visibleCount, setVisibleCount] = React.useState(1);

    const total = steps.length;
    const completed = Math.max(0, visibleCount - 1);
    const progress = Math.round((completed / total) * 100);

    const canAdvance = visibleCount < total + 1;
    const showAll = visibleCount > total;

    const handlePrevious = () => {
        setVisibleCount((c) => Math.max(1, c - 1));
    };

    const handleNext = () => {
        setVisibleCount((c) => Math.min(total + 1, c + 1));
    };

    return (
        <>
            <PageHeader
                title="Getting started"
                description="A walkthrough for pilots and controllers to get onto the network."
                imageSrc="/hero/hero_4.png"
            />

            <div className="space-y-6">
                {/* What is VATSIM + VATSIM Germany */}
                <Card className="rounded-2xl">
                    <CardContent>
                        <div className="grid gap-6 md:grid-cols-2">
                            <section className="space-y-2">
                                <h2 className="font-medium">What is VATSIM?</h2>
                                <div className="text-muted-foreground text-sm">
                                    <p>
                                        VATSIM (short for the Virtual Air Traffic Simulation Network) is a completely
                                        free online platform which allows virtual pilots, wherever they are in the
                                        world, to connect their flight simulators into one shared virtual world.
                                    </p>
                                    <br />
                                    <p>
                                        VATSIM also simulates air traffic control in this virtual world, creating the
                                        ultimate as-real-as-it-gets experience for you, the virtual aviation enthusiast.
                                    </p>
                                    <br />
                                    <p>
                                        The VATSIM world is divided into Regions, then Divisions and finally local
                                        facilities, which take on a number of different names depending on where you are
                                        in the world.
                                    </p>
                                </div>
                            </section>
                            <section className="space-y-2">
                                <h2 className="font-medium">What is vatger?</h2>
                                <div className="text-muted-foreground text-sm">
                                    <p>Vatger is the German subdivision of VATSIM.</p>
                                    <br />
                                    <p>
                                        If you’re interested in being a virtual ATC, vatger is one of the most
                                        structured and respected training divisions on the network.
                                    </p>
                                    <br />
                                    <p>
                                        If you’re a pilot, you benefit from realistic ATC when flying in/out of Germany.
                                    </p>
                                    <br />
                                    <p>
                                        Everything is volunteer-based, and realism is a big deal — they closely mirror
                                        real German aviation rules, phraseology, and airspace structure.
                                    </p>
                                </div>
                            </section>
                        </div>
                    </CardContent>
                </Card>

                {/* Progress + Step-by-step */}
                <Card className="rounded-2xl">
                    <CardHeader>
                        <div className="space-y-2">
                            <div className="text-muted-foreground flex items-center justify-between text-xs">
                                <span>
                                    Progress: {completed}/{total}
                                </span>
                                <span>{progress}%</span>
                            </div>
                            <Progress value={progress} />
                        </div>
                    </CardHeader>

                    <CardContent>
                        <div className="space-y-3">
                            {steps.slice(0, Math.min(visibleCount, total)).map((step, idx) => {
                                const isDone = idx < completed;
                                const isCurrent = idx === completed && !showAll;

                                const state: 'done' | 'current' | 'locked' = isDone
                                    ? 'done'
                                    : isCurrent
                                      ? 'current'
                                      : 'locked';

                                return (
                                    <div
                                        key={step.title}
                                        className="rounded-2xl border p-4 shadow-sm"
                                        aria-current={isCurrent ? 'step' : undefined}
                                    >
                                        <div className="flex items-start gap-3">
                                            <div className="mt-0.5">
                                                <StepIcon state={state} />
                                            </div>
                                            <div className="min-w-0 flex-1">
                                                <div className="flex items-baseline justify-between gap-4">
                                                    <p className="text-sm font-medium">{step.title}</p>
                                                </div>
                                                <div className="mt-2">{step.description}</div>

                                                <div className={'flex gap-2'}>
                                                    <Button
                                                        onClick={handlePrevious}
                                                        variant={'secondary'}
                                                        className={`mt-7 rounded-xl ${visibleCount === 1 || isDone ? 'hidden' : ''}`}
                                                    >
                                                        Previous
                                                    </Button>

                                                    <Button
                                                        onClick={handleNext}
                                                        disabled={!canAdvance}
                                                        className={`mt-7 rounded-xl ${isDone ? 'hidden' : ''}`}
                                                    >
                                                        {showAll ? 'Completed' : 'Next'}
                                                    </Button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}

                            {showAll ? (
                                <div className="rounded-2xl border p-4">
                                    <div className="flex items-start gap-3">
                                        <div className="mt-0.5">
                                            <CheckCircle2
                                                className="h-5 w-5 text-green-600"
                                                aria-hidden="true"
                                            />
                                        </div>
                                        <div className="space-y-1">
                                            <p className="text-sm font-medium">All steps completed</p>
                                            <p className="text-muted-foreground text-sm">
                                                You can now connect as a pilot or begin local ATC onboarding.
                                            </p>
                                            TODO
                                            <ul className="text-muted-foreground list-disc pl-5 text-sm">
                                                <li>Infos zu ATC/Pilot Training</li>
                                                <li>Links/Buttons zu Discord, Forum etc.</li>
                                                <li>etc.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            ) : null}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
