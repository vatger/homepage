import { loadPublicLivewire } from "./livewire-public";
import { laravelFireNoty, showNoty } from "./noty";
import { initializePublicUi } from "./public-ui";

initializePublicUi();
loadPublicLivewire();

window.showNoty = showNoty;
laravelFireNoty();
