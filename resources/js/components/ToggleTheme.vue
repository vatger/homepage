<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useColorMode } from '@vueuse/core';
import { Moon, Sun } from 'lucide-vue-next';
const mode = useColorMode();
</script>

<template>
  <Button
    @click="mode = mode === 'dark' ? 'light' : 'dark'"
    size="sm"
    variant="ghost"
    class=""
  >
    <div
      v-if="mode == 'light'"
      class="flex gap-2"
    >
      <Moon class="size-5" />
      <span class="block lg:hidden"> Dark </span>
    </div>

    <div
      v-else="mode == 'dark'"
      class="flex gap-2"
    >
      <Sun class="size-5" />
      <span class="block lg:hidden">Light</span>
    </div>

    <span class="sr-only">Toggle theme</span>
  </Button>
</template>
