<template>
    <form role="form" method="post" @submit.prevent="submit" @keydown="clearErrors($event.target.name)">
        <adminlte-input-text name="name" :form="form"></adminlte-input-text>

        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary" :disabled="form.submitting || form.errors.any()">Update</button>
        </div>
    </form>
</template>


<script>

  import { mapGetters } from 'vuex'
  import { AdminlteInputTextComponent } from 'acacha-adminlte-vue-forms'

  export default {
    name: 'UserForm',
    components: { AdminlteInputTextComponent },
    computed: {
      ...mapGetters({
        form: 'acacha-forms/form'
      }),
    },
    methods: {
      submit () {
       console.log('SUBMIT!!!!!!!!!!')
       this.$store.dispatch('acacha-forms/put','/api/v1/user').then( response => {
         console.log(response)
         console.log(response.data)
       }).catch( error => {
         if (error.response.status === 422) return
         this.flash('' + error, 'Oooppssss something went wrong!', 'danger', 'ban');
       })
      },
      clearErrors (fieldName) {
        if ( !fieldName ) return
        this.$store.dispatch('acacha-forms/clearErrorAction', fieldName)
      },
      flash(message, title, color, icon) {
        if (typeof window.flash === "function") {
          window.flash(message, title, color, icon)
        } else {
          console.log('error!')
//          this.showAlert(message, title, color, icon)
        }
      }
    }
  }
</script>
