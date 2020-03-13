<template>
  <div class="card">
    <div class="card-header">Users <button type="button" class="btn btn-primary float-right" data-toggle="collapse" data-target="#collapsePercentage" aria-expanded="false" aria-controls="collapsePercentage">Returning Users</button></div>
    <div class="card-body table-responsive">  
      <table class="table collapse" id="collapsePercentage">
        <thead>
          <tr>
            <th>
              <div class="row">
                <div class="col">
                  {{returning_percentage}}% of the users returned back at least
                  <input type="number" class="form-control inline-block" style="width: 10%;" v-model="returning_times" min="0">
                  {{returning_times > 1 || returning_times == 0 ? "times" : "time"}}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  between
                  <input type="datetime-local" class="form-control inline-block" style="width: 75%;" v-model="start_time">
                </div>
                <div class="col">
                  and
                  <input type="datetime-local" class="form-control inline-block" style="width: 75%;" v-model="end_time">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <button type="button" class="btn btn-primary" @click="getDetails">Calculate</button>
                </div>
              </div>
            </th>
          </tr>
        </thead>
      </table>
      
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
          <tr class="dontanimate">
            <td colspan="100" class="text-center card-bg-secondary py-1"><button type="button" class="btn btn-link" v-on:click.prevent="loadMore()" :disabled="currentPage >= totalPages">Load Older Entries</button></td>
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
            start_time: null,
            end_time: null,
            currentPage: 1,
            nextPageUrl: null,
            totalPages: 0,
            ready: false,
        };
    },
    created() {
        this.start_time = this.moment().subtract(2, "week").format("YYYY-MM-DD\THH:mm")
        this.end_time = this.moment().format("YYYY-MM-DD\THH:mm")

        this.axios.get(`${this.BaseUrl}/stats/users/login`).then((response) => {
            this.users = response.data.data;

            this.totalPages = response.data.last_page;
            this.nextPageUrl = response.data.next_page_url;
            
            this.getPercentage();

            this.ready = true;
        });
    },
    methods: {
      getPercentage() {
        if (!this.returning_times || !this.start_time || !this.end_time) {
          return;
        }
        
        this.axios.get(`${this.BaseUrl}/stats/users/returning?times=${this.returning_times}&from=${this.start_time}&to=${this.end_time}`).then((response) => {
            this.returning_percentage = response.data.percentage;
        });
      },
      getDetails() {
        this.ready = false;

        this.getPercentage();

        this.axios.get(`${this.BaseUrl}/stats/users/login?times=${this.returning_times}&from=${this.start_time}&to=${this.end_time}`).then((response) => {
          this.users = response.data.data;

          this.totalPages = response.data.last_page;
          this.currentPage = response.data.current_page;
          this.nextPageUrl = response.data.next_page_url;

          this.ready = true;
        });
      },
      loadMore() {
        this.axios.get(this.nextPageUrl)
          .then(response => {
            this.users = this.users.concat(response.data.data);
            this.currentPage = response.data.current_page;
            this.nextPageUrl = response.data.next_page_url;
          });
      }
    }
};
</script>