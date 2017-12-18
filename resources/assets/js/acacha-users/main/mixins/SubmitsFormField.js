export default {
  methods: {
    submitFormField (action, uri, formField) {
      this.send(action, uri).then(response => {
        this.success(formField + ' changed correctly!')
      }).catch(error => {
        if (error.response.status === 422) return
        this.flash('' + error, 'Oooppssss something went wrong!', 'danger', 'fa-ban')
      })
    }
  }
}
