require('./bootstrap');
require('./sidebar');

import { createApp } from 'vue';
import TextEditorComponent from './components/TextEditorComponent.vue';
import ImageUploadComponent from './components/ImageUploadComponent.vue';

createApp({
  components: {
    TextEditorComponent,
    ImageUploadComponent,
  },
}).mount('#app');
