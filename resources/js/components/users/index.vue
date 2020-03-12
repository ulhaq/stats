<template>
  <div class="card">
    <div class="card-header">Users</div>
    <div class="card-body table-responsive">      
      <loading v-if="!ready" />

      <table class="table light-bg text-center" v-if="ready && !users.length">
          <tr>
            <td>We didn't find anything - just empty space.</td>
          </tr>
      </table>

      <table class="table" v-if="ready && users.length">
        <thead>
          <tr>
            <th>{{returning_percentage}}% of the users return back at least <input type="number" class="form-control" style="display: inline-block; width: 7%;" v-model="returning_times" @change="getPercentage" min="0"> times</th>
          </tr>
        </thead>
      </table>

      <table class="table table-hover" v-if="ready && users.length">
        <thead>
          <tr>
            <th scope="col"># User</th>
            <th scope="col">Total Sessions</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.user">
            <td><span class="badge badge-secondary">{{user.user}}</span></td>
            <td>{{user.total}}</td>
            <td><router-link :to="{name: 'user-preview', params: {user: user.user}}" class="nav-link control-action"><svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 22 16"><path d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path></svg></router-link></td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            returning_percentage: 0,
            returning_times: 1,
            ready: false,
        };
    },
    created() {
        this.axios.get(`${this.BaseUrl}/stats/users/login`).then((response) => {
            this.users = response.data.counts;
            
            this.ready = true;
        });

        this.getPercentage();
    },
    methods: {
      getPercentage() {
        if (!this.returning_times) {
          return;
        }

        this.axios.get(`${this.BaseUrl}/stats/users/returning?times=${this.returning_times}`).then((response) => {
            this.returning_percentage = response.data.percentage;
        });
      }
    }
};
</script>