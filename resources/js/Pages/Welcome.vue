<script setup>
import Benefits from '@/components/Benefits.vue';
import Community from '@/components/Community.vue';
import Contact from '@/components/Contact.vue';
import Features from '@/components/Features.vue';
import Footer from '@/components/Footer.vue';
import HowItWorks from '@/components/HowItWorks.vue';
import Navbar from '@/components/Navbar.vue';
import Pricing from '@/components/Pricing.vue';
import Services from '@/components/Services.vue';
import Team from '@/components/Team.vue';
import Testimonials from '@/components/Testimonials.vue';
</script>

<template>
  <div class="flex min-h-screen flex-col">
    <Navbar />
    <main class="flex-1">
      <div class="mx-auto max-w-7xl px-4">
        <Benefits />
        <Features />
        <Services />
        <HowItWorks />
        <Testimonials />
        <Team />
        <Community />
        <Pricing />
        <Contact />
      </div>
    </main>
    <Footer />
  </div>
</template>
