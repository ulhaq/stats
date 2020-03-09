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

      <table class="table table-hover" v-if="ready && users.length">
        <thead>
          <tr>
            <th scope="col"># User</th>
            <th scope="col">Total Logins</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.user">
            <td><span class="badge badge-secondary">{{user.user}}</span></td>
            <td>{{user.total}}</td>
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
            ready: false,
        };
    },
    created() {
        this.axios.get(`${this.BaseUrl}/stats/users/login`).then((response) => {
            this.users = response.data.counts;
            
            this.ready = true;
        });
    }
};
</script>