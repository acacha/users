<template>
    <form id="theForm" class="simform" autocomplete="off">
        <div class="simform-inner" :class="{ hide : form.succeeded }">
            <slot :form="form"></slot>
            <div class="controls">
                <button class="next show" aria-label="Next" @click.prevent="submit()" ></button>
                <span class="error-message"
                      :class="{ show : form.errors.has('email') }"
                      v-text="form.errors.get('email')">
                </span>
            </div>
        </div>
        <span class="final-message" :class="{ show : form.succeeded }">
            <slot name="message">Form send ok!</slot>
        </span>
    </form>
</template>

<style src="./full-screen-forms.css"></style>

<script>

  import Form from './mixins/Form'

  export default {
    mixins: [Form],
    methods: {
      submit() {
        this.form.post(this.apiUri)
          .then(response => {
            this.$emit('onSubmit',response)
          })
          .catch(error => {
            console.log(this.form.errors.all())
            this.$emit('onSubmitError',error)
          })
      },
    },
    mounted () {
      this.form.clearOnSubmit = false
    }
  }

</script>