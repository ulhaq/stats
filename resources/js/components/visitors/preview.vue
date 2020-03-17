<template>
  <div class="card">
    <div class="card-header">Visitor Details</div>
    <div class="card-body">
      <loading v-if="!ready" />

      <table class="table table-borderless light-bg" v-if="ready">
        <tbody>
          <tr>
            <th>Visitor</th>
            <td>{{this.$route.params.visitor}}</td>
          </tr>
          <tr>
            <th>
              <div class="form-row">
                <div class="col">Sessions from
                  <input type="datetime-local" class="form-control" v-model="start_time" @change="getSessionsBetween">
                </div>
                <div class="col">
                  to
                  <input type="datetime-local" class="form-control" v-model="end_time" @change="getSessionsBetween">
                </div>
              </div>
            </th>
            <td>{{sessionsBetween}}</td>
          </tr>
          <tr class="pointer" data-toggle="collapse" data-target="#sessionDetails" aria-expanded="false" aria-controls="sessionDetails">
            <th>Total Sessions</th>
            <td>{{entry.length}}</td>
          </tr>
        </tbody>
      </table>
      <div class="collapse" id="sessionDetails">
        <div class="card card-body table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Client</th>
                <th scope="col">Platform</th>
                <th scope="col">Started</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody v-for="session in entry" :key="session.id">
              <tr>
                <td><span class="badge badge-secondary">{{session.id}}</span></td>
                <td>{{session.client}}</td>
                <td>{{session.platform}}</td>
                <td>{{utcToLocal(session.created_at).fromNow()}}</td>
                <td><router-link :to="{name: 'session-preview', params: {id: session.id}}" class="nav-link control-action"><svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 22 16"><path d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path></svg></router-link></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            entry: {
            },
            start_time: null,
            end_time: null,
            sessionsBetween: 0,
            ready: false,
        };
    },
    created() {
        this.axios.get(`${this.BaseUrl}/stats/visitors/${this.$route.params.visitor}`).then((response) => {
            this.entry = response.data;

            this.start_time = this.utcToLocal(response.data[response.data.length-1].created_at).format("YYYY-MM-DD\THH:mm");
            this.end_time = this.utcToLocal(response.data[0].created_at).format("YYYY-MM-DD\THH:mm");
            this.sessionsBetween = response.data.length;
            
            this.ready = true;
        });
    },
    methods: {
      getSessionsBetween(){
        this.axios.get(`${this.BaseUrl}/stats/visitors/${this.$route.params.visitor}?from=${this.start_time}&to=${this.end_time}`).then((response) => {
            this.sessionsBetween = response.data.length;
        });
      }
    }
};
</script>