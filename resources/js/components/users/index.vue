<template>
  <div class="card">
    <div class="card-header">Users <router-link :to="{name: 'user-register'}" class="float-right btn btn-primary"><svg class="bi bi-person-plus-fill" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 100-6 3 3 0 000 6zm7.5-3a.5.5 0 01.5.5v2a.5.5 0 01-.5.5h-2a.5.5 0 010-1H13V5.5a.5.5 0 01.5-.5z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M13 7.5a.5.5 0 01.5-.5h2a.5.5 0 010 1H14v1.5a.5.5 0 01-1 0v-2z" clip-rule="evenodd"/></svg></router-link></div>
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
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Created</th>
            <th scope="col">Updated</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td><span class="badge badge-secondary">{{user.id}}</span></td>
            <td>{{user.name}}</td>
            <td>{{user.email}}</td>
            <td :title="utcToLocal(user.created_at)">{{utcToLocal(user.created_at).fromNow()}}</td>
            <td :title="utcToLocal(user.updated_at)">{{utcToLocal(user.updated_at).fromNow()}}</td>
            <td><a href="" @click.prevent="deleteEntry('users', user.id)" class="control-action"><svg class="bi bi-trash" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/></svg></a></td>
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
            currentPage: 1,
            nextPageUrl: null,
            totalPages: 0,
            ready: false,
        };
    },
    created() {
        this.ready = false;

        this.loadData();
    },
    methods: {
      loadData() {
        this.axios.get(`${this.BaseUrl}/users`).then((response) => {
          this.users = response.data.data;
          this.totalPages = response.data.meta.last_page;
          this.nextPageUrl = response.data.links.next;
            
          this.ready = true;
        });
      },
      loadMore() {
        this.axios.get(this.nextPageUrl).then(response => {
            this.users = this.users.concat(response.data.data);
            this.currentPage = response.data.meta.current_page;
            this.nextPageUrl = response.data.links.next;
          });
      },
    }
};
</script>