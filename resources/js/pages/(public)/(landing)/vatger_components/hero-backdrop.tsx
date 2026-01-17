import Image from 'next/image';
import type React from 'react';

export function HeroBackdrop({ children }: { children: React.ReactNode }) {
    return (
        <div className="relative overflow-hidden bg-black">
            <div className="absolute inset-0 z-1">
                <div className="absolute -inset-6">
                    <Image
                        src="/hero/hero_3.png"
                        alt=""
                        fill
                        priority
                        className="object-cover scale-[1.05]"
                    />
                </div>
            </div>

            <div className="pointer-events-none absolute inset-0 z-2">
                <div className="absolute inset-0 bg-black/70 mix-blend-multiply" />
                <div className="absolute inset-0 bg-linear-to-br from-accent-500/20 via-transparent to-primary-900/60" />
            </div>

            <div className="relative z-3">{children}</div>
        </div>
    );
}
