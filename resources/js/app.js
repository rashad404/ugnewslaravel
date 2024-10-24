require('./bootstrap');
require('./sidebar');

import { createApp } from 'vue';
import TextEditorComponent from './components/TextEditorComponent.vue';
import ImageUploadComponent from './components/ImageUploadComponent.vue';
import { NewsReactionHandler } from './newsReactions';

document.addEventListener('DOMContentLoaded', () => {
    new NewsReactionHandler();
});

createApp({
  components: {
    TextEditorComponent,
    ImageUploadComponent,
  },
}).mount('#app');
