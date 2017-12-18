<template>
    <div>
        <adminlte-alert :alert="alert"></adminlte-alert>
        <form role="form" method="post" @submit.prevent="submit" @keydown="clearErrors($event.target.name)">
            <adminlte-input-text name="name" :form-name="formName"></adminlte-input-text>
            <adminlte-input-email :form-name="formName"></adminlte-input-email>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" :disabled="isDisabled()">Update</button>
            </div>
        </form>
    </div>
</template>


<script>
  import { AdminlteInputTextComponent, AdminlteInputEmailComponent } from 'acacha-adminlte-vue-forms'
  import {FlashMixin, DisabledSubmitMixin} from 'adminlte-vue'
  import Form, { FormsModule, UsesAcachaForms, registerForm, ClearErrorsMixin } from 'acacha-forms'
  import InteractsWithUser from './mixins/interactsWithUser'
  import SubmitsFormWithMessage from './mixins/SubmitsFormWithMessage'

  const UserNoPasswordForm = new Form({
    name: '',
    email: ''
  })

  const API_ENDPOINT = '/api/v1/user'
  const ACACHA_FORM = FormsModule(UserNoPasswordForm)
  const ACACHA_FORM_VUEX_MODULE = 'logged-user-no-password-form'

  export default {
    name: 'LoggedUserNoPasswordForm',
    components: { AdminlteInputTextComponent, AdminlteInputEmailComponent },
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
