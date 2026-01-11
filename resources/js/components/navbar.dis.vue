<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
} from '@/components/ui/navigation-menu';
</script>

<template>
  <NavigationMenu class="hidden lg:block">
    <NavigationMenuList>
      <NavigationMenuItem>
        <NavigationMenuTrigger class="bg-card text-base"> Features </NavigationMenuTrigger>
        <NavigationMenuContent>
          <div class="grid w-[600px] grid-cols-2 gap-5 p-4">
            <img
              src="https://www.radix-vue.com/logo.svg"
              alt="Beach"
              class="h-full w-full rounded-md object-cover"
            />
            <ul class="flex flex-col gap-2">
              <li
                v-for="{ title, description } in featureList"
                :key="title"
                class="rounded-md p-3 text-sm hover:bg-muted"
              >
                <p class="mb-1 leading-none font-semibold text-foreground">
                  {{ title }}
                </p>
                <p class="line-clamp-2 text-muted-foreground">
                  {{ description }}
                </p>
              </li>
            </ul>
          </div>
        </NavigationMenuContent>
      </NavigationMenuItem>

      <NavigationMenuItem>
        <NavigationMenuLink asChild>
          <Button
            v-for="{ href, label } in routeList"
            :key="label"
            as-child
            variant="ghost"
            class="justify-start text-base"
          >
            <a :href="href">
              {{ label }}
            </a>
          </Button>
        </NavigationMenuLink>
      </NavigationMenuItem>
    </NavigationMenuList>
  </NavigationMenu>
</template>

<style scoped></style>
