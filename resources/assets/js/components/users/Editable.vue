<template>
    <div :id="'editable-field-' + this.resource + '-' + this.id + '-' + this.name">
       <label class="control-label" v-if="!editing" @dblclick="toogleEditing" :id="'editable-field-label-' + this.resource + '-' + this.id + '-' + this.name">
            {{ value }}
            <i class="fa fa-edit" style="color: green;" v-if="!editing" @click="toogleEditing"></i>
        </label>
        <div class="input-group" v-if="editing">
            <input type="text" class="form-control" v-model="value" :name="this.name" :id="'input-edit-' + this.resource + '-' + this.id + '-field-' + this.name"
                   @keyup.esc="toogleEditing" @keyup.enter="save">
            <div class="input-group-addon bg-green">
                <i class="fa fa-check" @click="save" :id="'edit-button-' + this.resource + '-' + this.id + '-field-' + this.name"></i>
            </div>
            <div class="input-group-addon bg-red">
                <i class="fa fa-remove" @click="toogleEditing"></i>
            </div>
        </div>
    </div>
</template>

<script>

 export default {
    /*
    * The component's data.
    */
    data() {
      return {
        editing: this.edit,
        value: this.content,
        reloadEventName: this.reloadEvent
      };
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
      'reloadEvent': {
        type: String,
        default: "reload"
      }
    },
    methods: {
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
        axios.put(this.apiUrl + '/' + this.id, this.prepareSubmitData())
          .then(response => {
            this.$events.fire(this.reloadEvent)
          }, response => {
            toastr.info('Error saving! Error ' + response.status + ' ' + response.statusText)
          });
        this.toogleEditing();
      },

      /**
       * Prepare Save form data
       */
      prepareSubmitData() {
        return {
          [this.name]: this.value
        }
      }
    }
  }
</script>