import Image from 'next/image';

import vatgerLogo from '@/../public/static/assets/logo.svg?url';
import vatgerLogoDark from '@/../public/static/assets/logo_dark.svg?url';

export function Logo() {
    return (
        <>
            <Image
                src={vatgerLogo}
                alt="vatger Logo"
                width={433}
                height={138}
                className="absolute inset-0 block h-full w-full object-contain dark:hidden"
            />

            <Image
                src={vatgerLogoDark}
                alt="vatger Logo dark"
                width={433}
                height={138}
                className="absolute inset-0 hidden h-full w-full object-contain dark:block"
            />
        </>
    );
}
