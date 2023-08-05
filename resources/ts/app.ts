import * as bootstrap from 'bootstrap';

// Import custom Javascripts
//import { Pagination } from './pagination';

//window.Pagination = Pagination;

import initTemplate from './template';

initTemplate();

import { registerLibs, setupLibs } from './boot';

registerLibs();
setupLibs();

import { loadLivewireExtensions } from './livewire';

loadLivewireExtensions();

import { laravelFireNoty, registerNoty } from './noty';

registerNoty();
laravelFireNoty();
