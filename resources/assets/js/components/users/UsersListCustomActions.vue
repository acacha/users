<template>
  <div class="user-list-custom-actions">
    <button v-scroll-to="'#user-' + rowData.id + '-detail-row'" class="btn btn-sm btn-primary" :id="'show-user-' + rowData.id" @click="toogleShow('user',rowData)" :disabled="!laravel.user.can['view-users']"><i class="glyphicon glyphicon-zoom-in"></i></button>
    <button v-scroll-to="'#user-' + rowData.id + '-detail-row'" class="btn btn-sm btn-success" :id="'edit-user-' + rowData.id" @click="toogleEdit('user',rowData)" :disabled="!laravel.user.can['edit-users']"><i class="glyphicon glyphicon-pencil"></i></button>
    <button class="btn btn-sm btn-danger"  :id="'delete-user-' + rowData.id" @click="deleteResource('user',rowData)" :disabled="!laravel.user.can['delete-users']"><i class="glyphicon glyphicon-trash"></i></button>
  </div>
</template>

<script>
  import VueScrollTo from 'vue-scrollto'
  Vue.use(VueScrollTo)

  import CustomActions from './mixins/CustomActions'

  export default {
    mixins: [
      CustomActions
    ]
  }
</script>

<style src="./css/customActions.css"></style>
