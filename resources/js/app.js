require('./bootstrap');
require('./sidebar');

import { createApp } from 'vue';
import TextEditorComponent from './components/TextEditorComponent.vue';
import ImageUploadComponent from './components/ImageUploadComponent.vue';
import { initializeSubscription } from './subscription';

document.addEventListener('DOMContentLoaded', () => {
    initializeSubscription();
});

createApp({
  components: {
    TextEditorComponent,
    ImageUploadComponent,
  },
}).mount('#app');
