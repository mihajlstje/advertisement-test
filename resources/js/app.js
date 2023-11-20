import './bootstrap';
import 'flowbite';
import table from './table';
import image from './image';
import category from './category';
import customer from './customer';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('table', table)
Alpine.data('image', image)
Alpine.data('category', category)
Alpine.data('customer', customer)

Alpine.start();
