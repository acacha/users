<template>
    <div id="add-user">
        <adminlte-vue-box color="success" :collapsed="isCollapsed">
            <span slot="title">Create User</span>
            <div class="alert alert-success alert-dismissible" v-show="form.succeeded" id="create-user-result">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>User created!</strong> <br>
            </div>
            <form role="form" id="create-user-form" @submit.prevent="submit" @keydown="clearErrors($event.target.name)">
                <div class="box-body">
                    <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                        <label for="inputCreateUserName">Name</label>
                        <input type="text" class="form-control" id="inputCreateUserName" placeholder="Enter name"
                               name="name" value="" v-model="form.name">
                        <transition name="fade">
                            <span class="help-block" id="errorForInputCreateUserName" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                        </transition>
                    </div>
                    <div class="form-group" :class="{ 'has-error': form.errors.has('email') }">
                        <label for="inputCreateUserEmail">Email address</label>
                        <input type="email" class="form-control" id="inputCreateUserEmail" placeholder="Enter email"
                               name="email" value="" v-model="form.email">
                        <transition name="fade">
                            <span class="help-block" id="errorForInputCreateUserEmail" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                        </transition>
                    </div>
                    <div class="form-group" :class="{ 'has-error': form.errors.has('password') }">
                        <label for="inputCreateUserPassword">Password</label>
                        <input type="password" class="form-control" id="inputCreateUserPassword" placeholder="Password"
                               name="password" value="" v-model="form.password">
                        <transition name="fade">
                            <span class="help-block" id="errorForInputCreateUserPassword" v-if="form.errors.has('password')" v-text="form.errors.get('password')"></span>
                        </transition>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" id="create-user-button" class="btn btn-primary":disabled="form.errors.any()"><i v-if="form.submitting" id="create-user-spinner" class="fa fa-refresh fa-spin"></i> Create</button>
                </div>
            </form>
        </adminlte-vue-box>
    </div>
</template>

<style src="./css/fade.css"></style>

<script>

  import Form from 'acacha-forms'

  import {Box} from 'adminlte-vue'

  export default {
    data: function () {
      return {
        form: new Form({ name: '', email: '', password: '' }),
        isCollapsed: this.collapsed
      }
    },
    components: {
      'adminlte-vue-box' : Box
    },
    props: {
      apiUrl: {
        type: String,
        default: 'http://localhost:8080/api/v1/management/users/invitations'
      },
      collapsed: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      submit () {
        const API_URL = this.apiUrl
        this.form.post(API_URL)
          .then(response => {
            console.log('User created correctly')
            this.$events.fire('reload-user-list')
          })
          .catch(error => {
            console.log('User created error: ' + error)
            console.log(this.form.errors.all())
          })
      },
      clearErrors (name) {
        this.form.errors.clear(name)
      }
    },
    mounted () {
      this.form.clearOnSubmit = true
    },
    events: {
      'collapse-create-user' () {
        this.isCollapsed = true
      },
      'expand-create-user' () {
        this.isCollapsed = false
      }
    }
  }

</script>