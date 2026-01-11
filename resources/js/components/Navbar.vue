<script lang="ts" setup>
import { useColorMode } from '@vueuse/core';
import { Menu } from 'lucide-vue-next';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Sheet, SheetTrigger } from '@/components/ui/sheet';

import NavbarItems from '@/components/NavbarItems.vue';
import NavbarItemsMobile from '@/components/NavbarItemsMobile.vue';
import GithubIcon from '@/icons/GithubIcon.vue';
import ToggleTheme from './ToggleTheme.vue';

const mode = useColorMode({ initialValue: 'dark' });
const isOpen = ref(false);

const routes = [
  { href: '#testimonials', label: 'Testimonials' },
  { href: '#team', label: 'Team' },
  { href: '#contact', label: 'Contact' },
  { href: '#faq', label: 'FAQ' },
];
</script>

<template>
  <header class="sticky top-0 z-50 w-full bg-background/95 backdrop-blur supports-backdrop-filter:bg-background/60">
    <div class="mx-auto flex h-14 max-w-7xl items-center justify-between px-4">
      <!-- Left: Burger + Logo -->
      <div class="flex items-center gap-2">
        <!-- Mobile Menu -->
        <div class="lg:hidden">
          <Sheet v-model:open="isOpen">
            <SheetTrigger as-child>
              <Button
                size="icon"
                variant="ghost"
                aria-label="Open menu"
              >
                <Menu class="size-5" />
              </Button>
            </SheetTrigger>
            <NavbarItemsMobile></NavbarItemsMobile>
          </Sheet>
        </div>

        <!-- Logo -->
        <a
          href="/"
          class="flex items-center font-bold"
        >
          <img
            src="images/brand/logo_color_dark.svg"
            class="h-6"
          />
        </a>
      </div>

      <!-- Center: Desktop Nav -->
      <div class="hidden lg:flex">
        <NavbarItems />
      </div>

      <!-- Right: Actions -->
      <div class="flex">
        <ToggleTheme />
        <Button
          as-child
          size="icon"
          variant="ghost"
          aria-label="View on GitHub"
        >
          <a
            href="https://github.com/leoMirandaa/shadcn-vue-landing-page.git"
            target="_blank"
          >
            <GithubIcon class="size-5" />
          </a>
        </Button>
      </div>
    </div>
  </header>
</template>
