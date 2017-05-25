export default {
  data() {
    return {
      loggedUser : null
    }
  },
  methods: {
    getLoggedUser () {
      // if (window.LaravelUser) {
      //   console.log('Global user exists HEY!');
      //   this.user = window.LaravelUser
      // }
      // var component = this
      // axios.get('/api/user')
      //   .then(function (response) {
      //     component.loggedUser = response.data
      //     // window.LaravelUser = component.loggedUser
      //   })
      //   .catch(function (error) {
      //     console.log(error);
      //   });
    }
  },
  mounted() {
    this.getLoggedUser()
  }
}
