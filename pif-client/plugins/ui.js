export default ({ app }, inject) => {
  const ui = (data) => {
    // if (data.snackbar) {
    //   const snackbar = data.snackbar
    // }

    // if (res.response) {
    // }

    if (data.snackbar) app.store.dispatch('snackbar', data.snackbar)
  }
  inject('ui', ui)
}
