<template>
    <div id="migration_dashboard">
        <adminlte-vue-alert v-if="!connected" color="danger">Error connecting to source database. Please check configuration. To run the SSH tunel execute:
            <!--TODO Harcoded code!!! -->
            <pre>/usr/bin/ssh -o StrictHostKeyChecking=no  -N -i /home/sergi/.ssh/id_rsa -L 14852:127.0.0.1:3306 -p 22 sergi@192.168.50.180</pre>
        </adminlte-vue-alert>

        <h4>Source Database</h4>

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

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total users</span>
                        <span class="info-box-number">{{ totalUsers }}</span>
                    </div>
                </div>
            </div>

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Migrated</span>
                        <span class="info-box-number">{{ migratedUsers }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-times"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Not Migrated</span>
                        <span class="info-box-number">{{ notMigratedUsers }}</span>
                    </div>
                </div>
            </div>
        </div>

        <h4>Destination Database</h4>

        <div class="row">

            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total users</span>
                        <span class="info-box-number" id="totalUsersInDestination">{{ totalUsersInDestination }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Migrated</span>
                        <span class="info-box-number" id="totalMigratedUsersInDestination">{{ totalMigratedUsersInDestination }}</span>
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
        totalUsers: 0,
        migratedUsers: 0,
        totalUsersInDestination: 0,
        totalMigratedUsersInDestination: 0,
        connected: false
      }
    },
    props: {
      uri: {
        type: String,
        default: '-migration'
      }
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
      },
      notMigratedUsers: function()  {
        return (this.totalUsers - this.migratedUsers)
      }
    },
    methods: {
      checkConnection() {
        let component = this

        let dashboardRequest = new Form( {}, true )
        let apiUri = store.apiUri + this.uri + '/checkConnection'

        dashboardRequest.get(apiUri)
          .then( response => {
            component.connected = response.data.connected
          })
      },
      subscribeToRealTimeEvents() {
        var component = this
        this.$echo.private('acacha-users').
            listen('UserCreated', () => {
              this.boostrapDashboard()
            }).
            listen('UserRemoved', () => {
              this.boostrapDashboard()
            });
      },
      boostrapDashboard() {
        this.checkConnection()
        let component = this

        let dashboardRequest = new Form( {}, true )
        let apiUri = store.apiUri + this.uri + '/totalNumberOfUsers'
        dashboardRequest.get(apiUri)
          .then( response => {
            component.totalUsers = response.data.data
          })

        apiUri = store.apiUri + this.uri + '/totalNumberOfMigratedUsers'
        dashboardRequest.get(apiUri)
          .then( response => {
            component.migratedUsers = response.data.data
          })

        apiUri = store.apiUri + this.uri + '/totalNumberOfUsers/destination'
        dashboardRequest.get(apiUri)
          .then( response => {
            component.totalUsersInDestination = response.data.data
          })

        apiUri = store.apiUri + this.uri + '/totalNumberOfMigratedUsers/destination'
        dashboardRequest.get(apiUri)
          .then( response => {
            component.totalMigratedUsersInDestination = response.data.data
          })
      }
    },
    mounted() {
      this.boostrapDashboard()
      this.subscribeToRealTimeEvents()
    },
    events: {
      'update-user-migration-dashboard' () {
        Vue.nextTick(() => this.boostrapDashboard())
      }
    }
  }
</script>