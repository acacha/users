<template>
  <div @click="onClick">
    <div class="inline field">
      <label>Name: </label>
      <span>{{rowData.name}}</span>
    </div>
    <div class="inline field">
      <label>Email: </label>
      <span>{{rowData.email}}</span>
    </div>
    <div class="inline field">
      <label>Created at: </label>
      <span>{{rowData.created_at}}</span>
    </div>
    <div class="inline field">
      <label>Updated at: </label>
      <span>{{rowData.updated_at}}</span>
    </div>
  </div>
</template>

<script>
  import DetailRow from './DetailRow'

  export default {
    mixins: [
      DetailRow
    ]
  }
</script>
