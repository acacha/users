<template>
    <div class="filter-bar" id="users-list-filter-bar">
      <form class="form-inline">
        <div class="form-group">
          <label>Search for:</label>
          <input type="text" v-model="filterText" class="form-control" @keyup.enter="doFilter" placeholder="name or email">
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
        this.$events.fire('filter-set-users-list', this.filterText)
      },
      resetFilter () {
        this.filterText = ''
        this.$events.fire('filter-reset-users-list')
      },
      reload () {
        this.$events.fire('reload-users-list')
      }
    }
  }
</script>
<style src="./css/filterBar.css"></style>
