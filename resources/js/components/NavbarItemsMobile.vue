<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { SheetContent, SheetHeader, SheetTitle } from '@/components/ui/sheet';

const link_data = [
  { label: 'Home', href: '/' },
  { label: 'Components', href: '#components' },
  { label: 'Docs', href: '/docs' },
  { label: 'Blog', href: '#blog' },
  { label: 'Contact', href: '#contact' },
];
</script>
<template>
  <SheetContent
    side="left"
    class="flex flex-col justify-between"
  >
    <!-- Top -->
    <div>
      <SheetHeader>
        <SheetTitle class="text-lg font-bold"> Menu </SheetTitle>
      </SheetHeader>

      <div class="mt-4 flex flex-col gap-1">
        <Button
          v-for="link in link_data"
          :key="link.label"
          as-child
          variant="ghost"
          class="justify-start text-base"
        >
          <a :href="link.href">
            {{ link.label }}
          </a>
        </Button>
      </div>
    </div>

    <!-- Bottom -->
    <SheetFooter class="flex-col items-start gap-2">
      <Separator />
      <p class="text-sm text-muted-foreground">© 2026 ShadcnVue</p>
    </SheetFooter>
  </SheetContent>
</template>

<style scoped></style>
