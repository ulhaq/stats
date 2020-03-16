<template>
  <div class="card">
    <div class="card-header">Sessions <button type="button" title="Refreh" @click="loadData()" class="float-right btn btn-primary"><svg class="bi bi-arrow-repeat" width="1.5em" height="1.5em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 9.5a.5.5 0 00-.5.5 6.5 6.5 0 0012.13 3.25.5.5 0 00-.866-.5A5.5 5.5 0 014.5 10a.5.5 0 00-.5-.5z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M4.354 9.146a.5.5 0 00-.708 0l-2 2a.5.5 0 00.708.708L4 10.207l1.646 1.647a.5.5 0 00.708-.708l-2-2zM15.947 10.5a.5.5 0 00.5-.5 6.5 6.5 0 00-12.13-3.25.5.5 0 10.866.5A5.5 5.5 0 0115.448 10a.5.5 0 00.5.5z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M18.354 8.146a.5.5 0 00-.708 0L16 9.793l-1.646-1.647a.5.5 0 00-.708.708l2 2a.5.5 0 00.708 0l2-2a.5.5 0 000-.708z" clip-rule="evenodd"></path></svg></button></div>
    <div class="card-body table-responsive">      
      <loading v-if="!ready" />

      <table class="table light-bg text-center" v-if="ready && !sessions.length">
          <tr>
            <td>We didn't find anything - just empty space.</td>
          </tr>
      </table>
      
      <table class="table table-hover" v-if="ready && sessions.length">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">User</th>
            <th scope="col">Client</th>
            <th scope="col">Platform</th>
            <th scope="col">Started</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="session in sessions" :key="session.id">
            <td><span class="badge badge-secondary">{{session.id}}</span></td>
            <td>{{session.user}}</td>
            <td>{{session.client}}</td>
            <td>{{session.platform}}</td>
            <td>{{utcToLocal(session.created_at).fromNow()}}</td>
            <td><router-link :to="{name: 'session-preview', params: {id: session.id}}" class="nav-link control-action"><svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 22 16"><path d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path></svg></router-link></td>
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
            sessions: [],
            currentPage: 1,
            nextPageUrl: null,
            totalPages: 0,
            ready: false,
        };
    },
    created() {
        this.loadData();
    },
    methods: {
      loadData() {
        this.ready = false;

        this.axios.get(`${this.BaseUrl}/sessions`).then((response) => {
          this.sessions = response.data.data;
          this.totalPages = response.data.meta.last_page;
          this.nextPageUrl = response.data.links.next;
            
          this.ready = true;
        });
      },
      loadMore() {
        this.axios.get(this.nextPageUrl)
          .then(response => {
            this.sessions = this.sessions.concat(response.data.data);
            this.currentPage = response.data.meta.current_page;
            this.nextPageUrl = response.data.links.next;
          });
      }
    }
};
</script>