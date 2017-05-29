<template>
    <div :id="'editable-field-' + this.resource + '-' + this.id + '-' + this.name">
       <label class="control-label" v-if="!editing" @dblclick="toogleEditing" :id="'editable-field-label-' + this.resource + '-' + this.id + '-' + this.name">
            {{ value }}
            <i class="fa fa-edit" style="color: green;" v-if="!editing" @click="toogleEditing"></i>
        </label>
        <div class="input-group" v-if="editing">
            <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                <input type="text" class="form-control" v-model="value" :name="this.name" :id="'input-edit-' + this.resource + '-' + this.id + '-field-' + this.name"
                       @keyup.esc="toogleEditing" @keyup.enter="save" @keydown="clearErrors($event.target.name)">
            </div>
            <div class="input-group-addon bg-green">
                <i v-if="form.submitting" id="editable-spinner" class="fa fa-refresh fa-spin"></i>
                <i v-else class="fa fa-check" @click="save" :id="'edit-button-' + this.resource + '-' + this.id + '-field-' + this.name"></i>
            </div>
            <div class="input-group-addon bg-red">
                <i class="fa fa-remove" @click="toogleEditing"></i>
            </div>
        </div>
        <transition name="fade">
            <span class="help-block text-red" :id="'errorForInput-' + this.resource + '-' + this.id + '-field-' + this.name" v-if="form.errors.has(this.name)" v-text="form.errors.get(this.name)"></span>
        </transition>
    </div>
</template>

<script>

 import Form from 'acacha-forms'

 export default {
    /*
    * The component's data.
    */
    data() {
      return {
        editing: this.edit,
        afterSaveEventName: this.afterSaveEvent,
        form: new Form({ [this.name] : this.content })
      };
    },
    computed: {
      value: {
        get: function () {
          return this.form[this.name]
        },
        set: function (newValue) {
          this.form[this.name] = newValue
        }
      }
    },
    props: {
      'resource': {
        type: String,
        required: true
      },
      'name': {
        type: String,
        required: true
      },
      'id': {
        type: Number,
        required: true
      },
      'apiUrl': {
        type: String,
        required: true
      },
      'edit': {
        type: Boolean,
        default: true
      },
      'content': {
        type: String,
        default: ""
      },
      'afterSaveEvent': {
        type: String,
        default: "reload"
      }
    },
    methods: {
      clearErrors (name) {
        this.form.errors.clear(name)
      },

      /**
       * Toogle edit state
       */
      toogleEditing() {
        this.editing = !this.editing;
      },

      /**
       * Save change
       */
      save() {
        var component = this
        this.form.put(this.apiUrl + '/' + this.id)
          .then(response => {
            console.log('Firing event: ' + this.afterSaveEventName)
            component.$events.fire(this.afterSaveEventName)
            component.toogleEditing();
          })
          .catch(error => {
            if(error.response.status != 422) toastr.info('Error saving! ' + error)
          })
      }
    }
  }
</script>

<style src="./css/fade.css"></style>
