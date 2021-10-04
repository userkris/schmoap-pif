<template lang="html">
  <v-snackbar
    v-model="snackbar"
    :timeout="timeout"
  >
    {{ cache }}

    <template v-slot:action="{ attrs }">
      <v-btn
        color="blue"
        text
        v-bind="attrs"
        @click="snackbar = false"
      >
        Close
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
export default {
  data () {
    return {
      snackbar: false,
      timeout: 2000,
      cache: false
    }
  },
  computed: {
    text ({ $store: { state: { ui } } }) {
      return ui.snackbar ? ui.snackbar : ''
    }
  },
  watch: {
    text (message) {
      if (message) {
        this.cache = message
        this.snackbar = true
      }
      this.clear()
    }
  },
  methods: {
    clear () {
      this.$store.dispatch('snackbarClear')
    }
  }
}
</script>
