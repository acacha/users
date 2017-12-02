<template>
    <ul class="timeline">
        <li v-for="event in events" :class="{ 'time-label' : event.type=='time-label' }">
            <template v-if=" event.type=='time-label'">
                <span class="bg-green">
                    {{ event.time }}
                </span>
            </template>
            <template v-else>
                <i v-if="event.icon || event.iconBackground" class="fa" :class="[event.icon, event.iconBackground]"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{ event.time }}</span>
                    <div v-if="event.header" class="timeline-header" :class="{ 'no-border' : event.noBorder }">
                        <div v-html="event.header"></div>
                    </div>
                    <div v-if="event.body" class="timeline-body">
                        <div v-html="event.body"></div>
                    </div>
                    <div v-if="event.footer" class="timeline-footer" >
                        <div v-html="event.footer"></div>
                    </div>
                </div>
            </template>
        </li>

        <!-- END timeline item -->
        <li>
            <i class="fa fa-clock-o bg-gray"></i>
        </li>
    </ul>
</template>

<script>
  import {Box} from 'adminlte-vue'

  export default {
    data() {
      return {
        events: [
          { type: 'time-label', time: '11 Feb. 2014'},
          { header: 'The user A USERNAME (id) has been created by USERNAME(id)' , time: '5 mins ago', icon: 'fa-user', iconBackground: 'bg-green' },
          { header: 'The user USERNAME (id) has been deleted by USERNAME(id)' , time: '12:04', icon: 'fa-user', iconBackground: 'bg-red'},
          { type: 'time-label', time: '10 Feb. 2014'},
          { header: 'The user USERNAME (id) has been deleted by USERNAME(id)' , time: '12:04', icon: 'fa-user', iconBackground: 'bg-red'},
          { header: 'The user USERNAME (id) changed FIELDNAME of User from OLDVALUE to NEWVALUE' , time: '12:03', icon: 'fa-user', iconBackground: 'bg-yellow'},
          { header: 'The user USERNAME (id) has been soft deleted by USERNAME(id)' , time: '12:02', icon: 'fa-user', iconBackground: 'bg-red'},
          { header: 'Prova' , time: '12:05', icon: 'fa-comments', iconBackground: 'bg-yellow' },
          { header: 'sad ads asd asd asd ' , time: '1:05', iconBackground: 'bg-blue'},
          { header: 'Nothing' , time: '1:03'},
          { type: 'time-label', time: '09 Feb. 2014'},
          { header: 'Header ', body: 'Here is and HTML header <b/>Negreta</b>' , time: '1:05', iconBackground: 'bg-blue'},
          { header: 'Header with <b/>HTML</b> ', icon: 'fa-comments', body: 'Here is and HTML header <b/>Negreta</b>', footer: 'Footer que tal!' , time: '1:05', iconBackground: 'bg-blue'},
          { header: 'Header ', body: 'Here is and HTML header <b/>Negreta</b>', footer: 'Footer with <b/>HTML</b>!' , time: '1:05', iconBackground: 'bg-blue', noBorder: true},
          { header: 'Buttons on footer', body: 'Here is and HTML header <b/>Negreta</b>', footer: '<a class="btn btn-primary btn-xs">Read more</a> <a class="btn btn-danger btn-xs">Other</a>' , time: '1:05', iconBackground: 'bg-blue', noBorder: true}
        ]
      }
    },
    props: {
      apiUri: {
        type: String,
        default: '/api/v1/management/users/invitations'
      },
      model: {
        type: String,
        required: true
      }
    },
    mounted() {
      console.log('Component mounted.')
    }
  }
</script>
