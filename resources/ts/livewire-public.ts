import Clipboard from "@ryangjchandler/alpine-clipboard";
import {
  Alpine,
  Component,
  Livewire,
} from "~vendor/livewire/livewire/dist/livewire.esm";
import { showNoty } from "./noty";
import { hidePublicModal, showPublicModal } from "./public-ui";

export function findLivewireComponent(name: string): Component | undefined {
  return Livewire.all().find((value: any) => value.name === name);
}

export function loadPublicLivewire() {
  Livewire.hook("commit", ({ succeed }) => {
    succeed(() => window.dispatchEvent(new Event("featherReplace")));
  });

  Alpine.plugin(Clipboard);
  Livewire.start();

  Livewire.on("livewire_showNoty", ({ message, type, timeout }) => {
    showNoty(message, type, timeout);
  });

  Livewire.on("livewire_showModal", ({ event }) => {
    const id = event?.detail?.dom_id;
    if (id) showPublicModal(id);
  });

  Livewire.on("livewire_hideModal", ({ event }) => {
    const id = event?.detail?.dom_id;
    if (id) hidePublicModal(id);
  });

  Livewire.on("profile_fir_changed", () => hidePublicModal("change-fir-modal"));
}
