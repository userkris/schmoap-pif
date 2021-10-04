export default {

  /* $snackbar */
  snackbar ({ commit }, message) {
    commit('snackbar', message)
  },
  snackbarClear ({ commit }) {
    commit('snackbar', false)
  }

}
