<template>
    <div>
        <adminlte-alert :alert="alert"></adminlte-alert>
        <form role="form" method="post" @submit.prevent="submit" @keydown="clearErrors($event.target.name)">
            <adminlte-input-text name="name"></adminlte-input-text>
            <adminlte-input-email></adminlte-input-email>
            <adminlte-input-password></adminlte-input-password>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" :disabled="isDisabled()">Update</button>
            </div>
        </form>
    </div>
</template>


<script>
  import { AdminlteInputTextComponent, AdminlteInputEmailComponent, AdminlteInputPasswordComponent } from 'acacha-adminlte-vue-forms'
  import {FlashMixin, DisabledSubmitMixin} from 'adminlte-vue'
  import Form, { FormsModule, UsesAcachaForms, registerForm, ClearErrorsMixin } from 'acacha-forms'
  import InteractsWithUser from './mixins/interactsWithUser'
  import SubmitsFormWithMessage from './mixins/SubmitsFormWithMessage'

  const UserForm = new Form({
    name: '',
    email: '',
    password: ''
  })

  const API_ENDPOINT = '/api/v1/user'
  const ACACHA_FORM = FormsModule(UserForm)
  const ACACHA_FORM_VUEX_MODULE = 'user-form'

  export default {
    name: 'UserForm',
    components: { AdminlteInputTextComponent, AdminlteInputEmailComponent, AdminlteInputPasswordComponent },
    mixins: [FlashMixin, DisabledSubmitMixin, InteractsWithUser, UsesAcachaForms, ClearErrorsMixin, SubmitsFormWithMessage],
    methods: {
      submit () {
        this.submitFormWithMessage('put', API_ENDPOINT, 'User changed ok!')
      },
      fillForm () {
        // Update Form object with Current User
        const fields = [
          {
            field: 'name',
            value: this.user.name
          },
          {
            field: 'email',
            value: this.user.email
          }
        ]
        this.updateForm(fields)
      }
    },
    beforeCreate () {
      registerForm(this, ACACHA_FORM_VUEX_MODULE, ACACHA_FORM)
    }
  }
</script>
