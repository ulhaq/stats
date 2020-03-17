<template>
  <div class="card">
    <div class="card-header">Visitors <div class="float-right"><button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapsePercentage" aria-expanded="false" aria-controls="collapsePercentage">Returning Visitors</button> <button type="button" title="Refreh" @click="loadData()" class="btn btn-primary"><svg class="bi bi-arrow-repeat" width="1.5em" height="1.5em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 9.5a.5.5 0 00-.5.5 6.5 6.5 0 0012.13 3.25.5.5 0 00-.866-.5A5.5 5.5 0 014.5 10a.5.5 0 00-.5-.5z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M4.354 9.146a.5.5 0 00-.708 0l-2 2a.5.5 0 00.708.708L4 10.207l1.646 1.647a.5.5 0 00.708-.708l-2-2zM15.947 10.5a.5.5 0 00.5-.5 6.5 6.5 0 00-12.13-3.25.5.5 0 10.866.5A5.5 5.5 0 0115.448 10a.5.5 0 00.5.5z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M18.354 8.146a.5.5 0 00-.708 0L16 9.793l-1.646-1.647a.5.5 0 00-.708.708l2 2a.5.5 0 00.708 0l2-2a.5.5 0 000-.708z" clip-rule="evenodd"></path></svg></button></div></div>
    <div class="card-body table-responsive">  
      <table class="table collapse" id="collapsePercentage">
        <thead>
          <tr>
            <th>
              <div class="row">
                <div class="col">
                  {{returning_percentage}}% of the visitors returned back at least
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

      <table class="table light-bg text-center" v-if="ready && !visitors.length">
        <tr>
          <td>We didn't find anything - just empty space.</td>
        </tr>
      </table>

      <table class="table table-hover" v-if="ready && visitors.length">
        <thead>
          <tr>
            <th scope="col"># Visitor</th>
            <th scope="col">Total Sessions</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="visitor in visitors" :key="visitor.visitor">
            <td><span class="badge badge-secondary">{{visitor.visitor}}</span></td>
            <td>{{visitor.total}}</td>
            <td><router-link :to="{name: 'visitor-preview', params: {visitor: visitor.visitor}}" class="nav-link control-action"><svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 22 16"><path d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path></svg></router-link></td>
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
            visitors: [],
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

        this.loadData();

        this.getPercentage();
    },
    methods: {
      loadData() {
        this.ready = false;

        this.axios.get(`${this.BaseUrl}/stats/visitors/login`).then((response) => {
          this.visitors = response.data.data;

          this.totalPages = response.data.last_page;
          this.nextPageUrl = response.data.next_page_url;
          
          this.ready = true;
        });
      },
      getPercentage() {
        if (!this.returning_times || !this.start_time || !this.end_time) {
          return;
        }
        
        this.axios.get(`${this.BaseUrl}/stats/visitors/returning?times=${this.returning_times}&from=${this.start_time}&to=${this.end_time}`).then((response) => {
            this.returning_percentage = response.data.percentage;
        });
      },
      getDetails() {
        this.ready = false;

        this.getPercentage();

        this.axios.get(`${this.BaseUrl}/stats/visitors/login?times=${this.returning_times}&from=${this.start_time}&to=${this.end_time}`).then((response) => {
          this.visitors = response.data.data;

          this.totalPages = response.data.last_page;
          this.currentPage = response.data.current_page;
          this.nextPageUrl = response.data.next_page_url;

          this.ready = true;
        });
      },
      loadMore() {
        this.axios.get(this.nextPageUrl)
          .then(response => {
            this.visitors = this.visitors.concat(response.data.data);
            this.currentPage = response.data.current_page;
            this.nextPageUrl = response.data.next_page_url;
          });
      }
    }
};
</script>