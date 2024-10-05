<template>
  <div class="col-span-1 row-span-4 relative flex justify-center items-center bg-gray-100 border-dashed border-4 border-gray-300 rounded-lg w-full h-full">
    <!-- Default or Selected Image -->
    <div v-if="selectedImage" class="w-full h-full relative">
      <img :src="selectedImage" class="object-cover w-full h-full" />
      <!-- Remove Image Button Positioned Over Image -->
      <button @click="removeImage" class="absolute top-2 right-2 bg-red-500 text-white text-sm px-4 py-2 rounded">
        {{ removeLabel }}
      </button>
    </div>
    <div v-else class="flex flex-col justify-center items-center w-full h-full">
      <div class="relative cursor-pointer flex flex-col items-center justify-center w-full h-full bg-blue-50">
        <input type="file" name="file" id="image" @change="onImageChange" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
        <div class="flex flex-col items-center justify-center">
          <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5L12 15m0 0l-4.5-4.5M12 15V3M5 19h14"></path>
          </svg>
          <p class="text-gray-500 mt-2 text-sm text-center">{{ chooseFileLabel }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    uploadLabel: {
      type: String,
      required: true
    },
    removeLabel: {
      type: String,
      required: true
    },
    chooseFileLabel: {
      type: String,
      required: true
    },
    currentImage: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      selectedImage: null, // Will store the selected image's URL
    }
  },
  mounted() {
    if (this.currentImage) {
      this.selectedImage = this.currentImage;
    }
  },
  methods: {
    onImageChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.selectedImage = URL.createObjectURL(file); // Create a URL for the selected file
        
        // Use document.querySelector to access the file input element from Blade
        const fileInput = document.querySelector('input[name="image"]');
        if (fileInput) {
          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(file);
          fileInput.files = dataTransfer.files;
        }
      }
    },
    removeImage() {
      this.selectedImage = null; // Reset the image preview
      
      // Clear the file input in the Blade element
      const fileInput = document.querySelector('input[name="image"]');
      if (fileInput) {
        fileInput.value = '';
      }
    }
  }
}
</script>
<style scoped>
/* Ensures the image fills the available space while maintaining aspect ratio */
.flex {
  min-height: 250px; /* Set a minimum height for the upload section */
}

.bg-blue-50 {
  background-color: #e0f2fe; /* A more professional, lighter blue for the background */
}

input[type="file"] {
  width: 100%;
  height: 100%;
}
</style>
