// Import custom Javascripts
//import { Pagination } from './pagination';

//window.Pagination = Pagination;

import initTemplate from './template';

initTemplate();

import { loadLivewireExtensions } from './livewire';

loadLivewireExtensions();

import { laravelFireNoty, showNoty } from './noty';

window['showNoty'] = showNoty;

laravelFireNoty();
