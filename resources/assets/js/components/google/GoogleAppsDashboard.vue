<template>
    <div id="google_apps_users_dashboard">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon"><i class="fa" :class="[connectionStatusIcon, connectionStatusColor]"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Status</span>
                        <span class="info-box-number" style="word-break: break-all;">{{ connectionStatus }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Admin User</span>
                        <span class="info-box-number"> {{userAdmin}} </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-envelope"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Pending Invitations</span>
                        <span class="info-box-number">TODO</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

  import store from '../Store'
  import Form from 'acacha-forms'

  export default {
    data() {
      return {
        connected: false
      }
    },
    props: {
      uri: {
        type: String,
        default: '-google'
      },
      userAdmin: {
        type: String,
        required: true
      },
    },
    computed: {
      connectionStatus: function()  {
        if (this.connected) return "Connected"
        return "Disconnected"
      },
      connectionStatusIcon: function()  {
        if (this.connected) return "fa-check"
        return "fa-times"
      },
      connectionStatusColor: function()  {
        if (this.connected) return "text-success"
        return "text-danger"
      }
    },
    methods: {
      checkConnection() {
        let component = this

        let dashboardRequest = new Form({}, true)
        let apiUri = store.apiUri + this.uri + '/check'
        dashboardRequest.get(apiUri)
          .then(response => {
            if (response.data.state == 'connected') component.connected = true
          })
      },
      boostrapDashboard() {
        this.checkConnection()
      }
    },
    mounted() {
      console.log();
      this.boostrapDashboard()
    }
  }
</script>
