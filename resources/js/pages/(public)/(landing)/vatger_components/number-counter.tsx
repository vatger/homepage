'use client';

import { type JSX, useEffect, useRef, useState } from 'react';

type CounterProps = {
    initial: number;
    target: number;
    duration: number; // ms
};

export function Counter({
    initial,
    target,
    duration,
}: CounterProps): JSX.Element {
    const [currentValue, setCurrentValue] = useState<number>(initial);
    const startRef = useRef<number | null>(null);
    const rafRef = useRef<number | null>(null);

    useEffect(() => {
        if (rafRef.current !== null) {
            cancelAnimationFrame(rafRef.current);
            rafRef.current = null;
        }
        startRef.current = null;

        const from = initial;
        const to = target;
        const diff = to - from;

        if (diff === 0 || duration <= 0) {
            setCurrentValue(to);
            return;
        }

        const tick = (ts: number) => {
            if (startRef.current === null) startRef.current = ts;

            const elapsed = ts - startRef.current;
            const t = Math.min(elapsed / duration, 1);

            setCurrentValue(Math.round(from + diff * t));

            if (t < 1) {
                rafRef.current = requestAnimationFrame(tick);
            } else {
                setCurrentValue(to);
                rafRef.current = null;
            }
        };

        rafRef.current = requestAnimationFrame(tick);

        return () => {
            if (rafRef.current !== null) cancelAnimationFrame(rafRef.current);
        };
    }, [initial, target, duration]);

    return <>{currentValue}</>;
}
