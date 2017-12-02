import axios from 'axios'

export default {
  methods: {
    performAction (data, uri, parameters, result, action = 'post', resultEvent = 'show-result') {
      this.performingAction = true
      var component = this
      let apiUri = component.store.apiUri + uri
      axios[action](apiUri, parameters)
        .then(function (response) {
          component.hideDialog()
          component.$events.fire(resultEvent, result)
        })
        .catch(function (error) {
          component.hideDialog()
          console.log(error)
        })
    }
  }
}
