import { Button } from '@/components/ui/button';
import Image from 'next/image';

export function WhoWeAre() {
    return (
        <section
            id="blog"
            className="bg-muted py-16 sm:py-16"
        >
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">
                {/* Section Header */}
                <div className="mx-auto mb-16 max-w-2xl text-center">
                    <h2 className="mb-4 text-3xl font-bold tracking-tight sm:text-4xl">Who We Are</h2>
                </div>

                <div className="gap-26 grid grid-cols-2">
                    <div>
                        <Image
                            src={'/hero/scope_holding.png'}
                            width={1920}
                            height={1080}
                            alt="Radar screen"
                            className="w-full rounded-xl shadow-lg transition-shadow hover:shadow-xl"
                        />
                    </div>

                    <div className="space-y-5">
                        <p>
                            VATSIM Germany is part of the VATSIM Europe Division, which, together with several other
                            national divisions, forms the VATSIM Europe, Middle East and Africa (EMEA) Region. This
                            regional structure ensures coordinated standards, training, and operational procedures
                            across a wide geographical area, while still allowing each division to focus on the specific
                            needs and characteristics of its local aviation environment.
                        </p>

                        <p>
                            As a member of the global VATSIM network, VATSIM Germany contributes to an international
                            online flight simulation community that connects virtual pilots and air traffic controllers
                            from all over the world in a shared, real-time environment. The network enables users to
                            experience realistic air traffic operations by simulating real-world airspace, airports,
                            routes, and procedures. Air traffic control services are provided by trained volunteer
                            controllers who operate according to real aviation rules and practices, creating a high
                            level of realism and immersion.
                        </p>

                        <Button>Get Started Now</Button>
                    </div>
                </div>
            </div>
        </section>
    );
}
