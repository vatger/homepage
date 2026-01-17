import Layout from '../layout';
import { FaqSection } from './components/faq-section';
import { HeroSection } from './components/hero-section';
import { LogoCarousel } from './components/logo-carousel';
import { StatsSection } from './components/stats-section';
import { HeroBackdrop } from './vatger_components/hero-backdrop';
import { WhoWeAre } from './vatger_components/who-we-are';
export default function LandingPageContent() {
    return (
        <Layout>
            {/* Main Content */}
            <main className="-mt-24">
                <HeroBackdrop>
                    <HeroSection />
                    <LogoCarousel />
                </HeroBackdrop>
                <StatsSection></StatsSection>

                <WhoWeAre />
                {/*
                <Suspense fallback={<h1>Loading Events...</h1>}>
                    <UpcomingEventsSection />
                </Suspense>
                */}

                <FaqSection />
            </main>
        </Layout>
    );
}
