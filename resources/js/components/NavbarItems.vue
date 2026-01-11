<script setup lang="ts">
import { Button as ListItem } from '@/components/ui/button';
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
  navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { CircleCheckIcon, CircleHelpIcon, CircleIcon } from 'lucide-vue-next';

type NavItem = {
  title: string;
  href: string;
  description?: string;
  icon?: any;
};

const components: NavItem[] = [
  {
    title: 'Alert Dialog',
    href: '/docs/primitives/alert-dialog',
    description: 'A modal dialog that interrupts the user with important content.',
  },
  {
    title: 'Hover Card',
    href: '/docs/primitives/hover-card',
    description: 'Preview content available behind a link.',
  },
  {
    title: 'Progress',
    href: '/docs/primitives/progress',
    description: 'Displays task completion progress.',
  },
  {
    title: 'Scroll Area',
    href: '/docs/primitives/scroll-area',
    description: 'Visually separates content.',
  },
  {
    title: 'Tabs',
    href: '/docs/primitives/tabs',
    description: 'Layered sections of content.',
  },
  {
    title: 'Tooltip',
    href: '/docs/primitives/tooltip',
    description: 'Displays contextual information.',
  },
];

const simpleLinks = ['Components', 'Documentation', 'Blocks'];

const iconLinks: NavItem[] = [
  { title: 'Backlog', href: '#', icon: CircleHelpIcon },
  { title: 'To Do', href: '#', icon: CircleIcon },
  { title: 'Done', href: '#', icon: CircleCheckIcon },
];
</script>

<template>
  <NavigationMenu :viewport="false">
    <NavigationMenuList>
      <!-- Home -->
      <NavigationMenuItem>
        <NavigationMenuTrigger>Home</NavigationMenuTrigger>
        <NavigationMenuContent>
          <ul class="grid gap-2 md:w-[400px] lg:w-[500px] lg:grid-cols-[.75fr_1fr]">
            <li class="row-span-3">
              <NavigationMenuLink as-child>
                <a
                  href="/"
                  class="flex h-full w-full flex-col justify-end rounded-md bg-linear-to-b from-muted/50 to-muted p-6"
                >
                  <div class="mb-2 text-lg font-medium">shadcn/ui</div>
                  <p class="text-sm text-muted-foreground">Beautifully designed components built with Tailwind CSS.</p>
                </a>
              </NavigationMenuLink>
            </li>

            <ListItem
              to="/docs"
              title="Introduction"
            >
              Re-usable components built with Radix UI.
            </ListItem>
            <ListItem
              to="/docs/installation"
              title="Installation"
            >
              How to install and structure your app.
            </ListItem>
            <ListItem
              to="/docs/primitives/typography"
              title="Typography"
            >
              Headings, paragraphs, lists, and more.
            </ListItem>
          </ul>
        </NavigationMenuContent>
      </NavigationMenuItem>

      <!-- Components -->
      <NavigationMenuItem>
        <NavigationMenuTrigger>Components</NavigationMenuTrigger>
        <NavigationMenuContent>
          <ul class="grid w-[400px] gap-2 md:w-[500px] md:grid-cols-2 lg:w-[600px]">
            <ListItem
              v-for="item in components"
              :key="item.title"
              :to="item.href"
              :title="item.title"
            >
              {{ item.description }}
            </ListItem>
          </ul>
        </NavigationMenuContent>
      </NavigationMenuItem>

      <!-- Docs -->
      <NavigationMenuItem>
        <NavigationMenuLink
          as-child
          :class="navigationMenuTriggerStyle()"
        >
          <a href="/docs">Docs</a>
        </NavigationMenuLink>
      </NavigationMenuItem>

      <!-- Simple -->
      <NavigationMenuItem>
        <NavigationMenuTrigger>Simple</NavigationMenuTrigger>
        <NavigationMenuContent>
          <ul class="grid w-[200px] gap-4">
            <li>
              <NavigationMenuLink
                v-for="link in simpleLinks"
                :key="link"
                as-child
              >
                <a href="#">{{ link }}</a>
              </NavigationMenuLink>
            </li>
          </ul>
        </NavigationMenuContent>
      </NavigationMenuItem>

      <!-- With Icon -->
      <NavigationMenuItem>
        <NavigationMenuTrigger>With Icon</NavigationMenuTrigger>
        <NavigationMenuContent>
          <ul class="grid w-[200px] gap-4">
            <li>
              <NavigationMenuLink
                v-for="item in iconLinks"
                :key="item.title"
                as-child
              >
                <a
                  href="#"
                  class="flex items-center gap-2"
                >
                  <component :is="item.icon" />
                  {{ item.title }}
                </a>
              </NavigationMenuLink>
            </li>
          </ul>
        </NavigationMenuContent>
      </NavigationMenuItem>
    </NavigationMenuList>
  </NavigationMenu>
</template>
