<template>
  <div class="tiptap-editor-container">
    <div v-if="editor" class="tiptap-control-group">
      <!-- Essential Buttons -->
      <div class="tiptap-button-group">
        <button type="button" 
          @click="editor.chain().focus().toggleBold().run()" 
          :disabled="!editor.can().chain().focus().toggleBold().run()" 
          :class="{ 'is-active': editor.isActive('bold') }">
          Bold
        </button>
        <button type="button" 
          @click="editor.chain().focus().toggleItalic().run()" 
          :disabled="!editor.can().chain().focus().toggleItalic().run()" 
          :class="{ 'is-active': editor.isActive('italic') }">
          Italic
        </button>
        <button type="button" 
          @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" 
          :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }">
          H1
        </button>

        <!-- More Button -->
        <button type="button" @click="showMore = !showMore">
          {{ showMore ? 'Less' : 'More' }}
        </button>
      </div>

      <!-- Hidden buttons: Toggle with More -->
      <div v-if="showMore" class="tiptap-button-group mt-2">
        <button type="button" @click="editor.chain().focus().toggleStrike().run()" :disabled="!editor.can().chain().focus().toggleStrike().run()" :class="{ 'is-active': editor.isActive('strike') }">
          Strike
        </button>
        <button type="button" @click="editor.chain().focus().toggleCode().run()" :disabled="!editor.can().chain().focus().toggleCode().run()" :class="{ 'is-active': editor.isActive('code') }">
          Code
        </button>
        <button type="button" @click="editor.chain().focus().toggleCodeBlock().run()" :class="{ 'is-active': editor.isActive('codeBlock') }">
          Code Block
        </button> 
        <button type="button" @click="editor.chain().focus().unsetAllMarks().run()">
          Clear marks
        </button>
        <button type="button" @click="editor.chain().focus().clearNodes().run()">
          Clear nodes
        </button>
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }">
          H2
        </button>
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }">
          H3
        </button>
        <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'is-active': editor.isActive('bulletList') }">
          Bullet list
        </button>
        <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'is-active': editor.isActive('orderedList') }">
          Ordered list
        </button>
        <button type="button" @click="editor.chain().focus().toggleBlockquote().run()" :class="{ 'is-active': editor.isActive('blockquote') }">
          Blockquote
        </button>
        <button type="button" @click="editor.chain().focus().setHorizontalRule().run()">
          Horizontal rule
        </button>
        <button type="button" @click="editor.chain().focus().setHardBreak().run()">
          Hard break
        </button>
        <button type="button" @click="editor.chain().focus().undo().run()" :disabled="!editor.can().chain().focus().undo().run()">
          Undo
        </button>
        <button type="button" @click="editor.chain().focus().redo().run()" :disabled="!editor.can().chain().focus().redo().run()">
          Redo
        </button>
        <button type="button" @click="editor.chain().focus().setColor('#958DF1').run()" :class="{ 'is-active': editor.isActive('textStyle', { color: '#958DF1' }) }">
          Purple
        </button>
      </div>
    </div>

    <editor-content :editor="editor" />
  </div>
</template>

<script>
import { Color } from '@tiptap/extension-color'
import ListItem from '@tiptap/extension-list-item'
import TextStyle from '@tiptap/extension-text-style'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import { Editor, EditorContent } from '@tiptap/vue-3'

export default {
  components: {
    EditorContent,
  },

  props: {
    initialContent: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      editor: null,
      showMore: false,
    }
  },

  mounted() {
    this.editor = new Editor({
      extensions: [
        Color.configure({ types: [TextStyle.name, ListItem.name] }),
        TextStyle.configure({ types: [ListItem.name] }),
        StarterKit,
        Placeholder.configure({
          placeholder: 'Start writing your news article here...',
        })
      ],
      content: this.initialContent,
      onUpdate: ({ editor }) => {
        // Update hidden input value when content changes
        document.getElementById('text_content').value = editor.getHTML()
      }
    })
  },

  beforeUnmount() {
    this.editor.destroy()
  },
}
</script>


<style lang="scss" scoped>
.tiptap-editor-container {
  font-family: Arial, sans-serif;

  .tiptap-control-group {
    margin-bottom: 1rem;
  }

  .tiptap-button-group {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;

    button {
      background-color: #f0f0f0;
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 0.5rem 1rem;
      cursor: pointer;
      font-size: 0.9rem;

      &:hover {
        background-color: #e0e0e0;
      }

      &.is-active {
        background-color: #d0d0d0;
        font-weight: bold;
      }

      &:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }
    }
  }
}

/* Tiptap editor styles */
:deep(.tiptap) {
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 1rem;
  min-height: 300px;

  > * + * {
    margin-top: 0.75em;
  }

  ul,
  ol {
    padding: 0 1rem;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    line-height: 1.1;
  }

  code {
    background-color: rgba(#616161, 0.1);
    color: #616161;
  }

  pre {
    background: #0D0D0D;
    color: #FFF;
    font-family: 'JetBrainsMono', monospace;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;

    code {
      color: inherit;
      padding: 0;
      background: none;
      font-size: 0.8rem;
    }
  }

  img {
    max-width: 100%;
    height: auto;
  }

  blockquote {
    padding-left: 1rem;
    border-left: 2px solid rgba(#0D0D0D, 0.1);
  }

  hr {
    border: none;
    border-top: 2px solid rgba(#0D0D0D, 0.1);
    margin: 2rem 0;
  }

  p.is-editor-empty:first-child::before {
    color: #adb5bd;
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
  }
}
</style>
