<template>
    <div>
        <adminlte-alert :alert="alert"></adminlte-alert>
        <form role="form" method="post" @submit.prevent="submit" @keydown="clearErrors($event.target.name)">
            <adminlte-input-text name="name" :form-name="formName"></adminlte-input-text>
            <adminlte-input-email :form-name="formName"></adminlte-input-email>
            <adminlte-input-password :form-name="formName"></adminlte-input-password>
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

  const LoggedUserForm = new Form({
    name: '',
    email: '',
    passsword: ''
  })

  const API_ENDPOINT = '/api/v1/user'
  const ACACHA_FORM = FormsModule(LoggedUserForm)
  const ACACHA_FORM_VUEX_MODULE = 'user-form'

  export default {
    name: 'LoggedUserForm',
    components: { AdminlteInputTextComponent, AdminlteInputEmailComponent, AdminlteInputPasswordComponent },
    mixins: [FlashMixin, DisabledSubmitMixin, UsesAcachaForms, InteractsWithUser, ClearErrorsMixin, SubmitsFormWithMessage],
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
