<template>
  <div class="card">
    <div class="card-header">User Details</div>
    <div class="card-body">
      <loading v-if="!ready" />

      <table class="table table-borderless light-bg" v-if="ready">
        <tbody>
          <tr>
            <th>User</th>
            <td>{{this.$route.params.user}}</td>
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
                <td>{{moment(session.created_at).fromNow()}}</td>
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
            ready: false,
        };
    },
    created() {
        this.axios.get(`${this.BaseUrl}/stats/users/${this.$route.params.user}`).then((response) => {
            this.entry = response.data;
            
            this.ready = true;
        });
    },
};
</script>