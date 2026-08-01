import { showNoty } from "./noty.ts";
import { hidePublicModal, showPublicModal } from "./public-ui";

import {
  Livewire,
  Alpine,
  Component,
  // @ts-ignore
} from "~vendor/livewire/livewire/dist/livewire.esm";
// @ts-ignore
import Clipboard from "@ryangjchandler/alpine-clipboard";

export function findLivewireComponent(name: string): Component {
  return Livewire.all().find((value: any) => value["name"] == name);
}

export function loadLivewireExtensions() {
  Livewire.interceptMessage(({ onSuccess }) => {
    onSuccess(() => {
      window.dispatchEvent(new Event("featherReplace"));
    });
  });

  Alpine.plugin(Clipboard);
  Livewire.start();

  Livewire.on("livewire_showNoty", ({ message, type, timeout }) => {
    showNoty(message, type, timeout);
  });

  Livewire.on("livewire_showModal", ({ event }) => {
    showPublicModal(event["detail"].dom_id);
  });

  Livewire.on("livewire_hideModal", ({ event }) => {
    hidePublicModal(event["detail"].dom_id);
  });

  Livewire.on("profile_fir_changed", () => {
    hidePublicModal("change-fir-modal");
  });
}
