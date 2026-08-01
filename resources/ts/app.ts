import { initializeAdminUi } from "./admin-ui";
import { initializePublicUi } from "./public-ui";

initializePublicUi();
initializeAdminUi();

import { loadLivewireExtensions } from "./livewire";

loadLivewireExtensions();

import { laravelFireNoty, showNoty } from "./noty";

window["showNoty"] = showNoty;

laravelFireNoty();
