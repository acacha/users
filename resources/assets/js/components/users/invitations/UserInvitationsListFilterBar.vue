<template>
    <div class="filter-bar" id="user-invitations-list-filter-bar" >
      <form class="form-inline">
        <div class="form-group">
          <label>Search for:</label>
          <input type="text" v-model="filterText" class="form-control" @keyup.enter="doFilter" placeholder="name, state or email">
          <button class="btn btn-primary" @click.prevent="doFilter">Go</button>
          <button class="btn" @click.prevent="resetFilter">Reset</button>
          <button class="btn" @click.prevent="reload"><i class="fa fa-refresh"></i></button>
        </div>
      </form>
    </div>
</template>

<script>
  export default {
    data () {
      return {
        filterText: ''
      }
    },
    methods: {
      doFilter () {
        this.$events.fire('filter-set-user-invitations-list', this.filterText)
      },
      resetFilter () {
        this.filterText = ''
        this.$events.fire('filter-reset-user-invitations-list')
      },
      reload () {
        this.$events.fire('reload-user-invitations-list')
      }
    }
  }
</script>
<style src="../css/filterBar.css"></style>

