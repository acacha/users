<template>
    <div id="create-user-via-invitation">
        <div v-if="finishing" id="create-user-via-invitation-finishing">
            <div class="callout callout-success">
                <h4>User created ok!</h4>
                <p><i id="redirecting-to-home" class="fa fa-refresh fa-spin"></i> Redirecting to home page....</p>
            </div>
        </div>
        <div v-else>
            <div class="callout callout-success">
                <h4>Thanks for accepting the user invitation {{ this.email }}!</h4>

                <p>Please fill the below form to finish user invitation process...</p>
            </div>

            <create-user :api-url="this.apiUri" @onSubmitOk="finish"
                         :email="this.email" :token="this.token"></create-user>
        </div>

    </div>
</template>

<style src="./css/fade.css"></style>

<script>

  import CreateUser from './CreateUser.vue'
  import axios from 'axios'

  export default {
    components: {
      'create-user' : CreateUser
    },
    data: function () {
      return {
        finishing: false
      }
    },
    props: {
      apiUri: {
        type: String,
        default: '/api/v1/management/users/user-invitation-accept'
      },
      email: {
        type: String,
        default: null
      },
      token: {
        type: String,
        default: null
      },
      urlToRedirect: {
        type: String,
        default: '/home'
      }
    },
    methods: {
      finish(password) {
        this.finishing = true
        var component = this
        axios.post('/login', {
          email: component.email,
          password: password
        })
        .then(function (response) {
          window.location = component.urlToRedirect
        })
        .catch(function (error) {
          console.log(error);
        });
      }
    }
  }

</script>