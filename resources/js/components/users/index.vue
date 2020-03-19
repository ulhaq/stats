<template>
  <div class="card">
    <div class="card-header">Users <router-link :to="{name: 'user-register'}" class="float-right btn btn-primary">Register a new User</router-link></div>
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
            <td><span :title="utcToLocal(user.created_at)">{{utcToLocal(user.created_at).fromNow()}}</span></td>
            <td><span :title="utcToLocal(user.updated_at)">{{utcToLocal(user.updated_at).fromNow()}}</span></td>
            <td><a href="" @click.prevent="deleteUser(user.id)"><svg class="bi bi-trash" width="1.2em" height="1.2em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"> <path d="M7.5 7.5A.5.5 0 018 8v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V8z"></path> <path fill-rule="evenodd" d="M16.5 5a1 1 0 01-1 1H15v9a2 2 0 01-2 2H7a2 2 0 01-2-2V6h-.5a1 1 0 01-1-1V4a1 1 0 011-1H8a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM6.118 6L6 6.059V15a1 1 0 001 1h6a1 1 0 001-1V6.059L13.882 6H6.118zM4.5 5V4h11v1h-11z" clip-rule="evenodd"></path></svg></a></td>
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
        this.loadData();
    },
    methods: {
      loadData() {
        this.ready = false;

        this.axios.get(`${this.BaseUrl}/users`).then((response) => {
          this.users = response.data.data;
          this.totalPages = response.data.meta.last_page;
          this.nextPageUrl = response.data.links.next;
            
          this.ready = true;
        });
      },
      loadMore() {
        this.axios.get(this.nextPageUrl)
          .then(response => {
            this.users = this.users.concat(response.data.data);
            this.currentPage = response.data.meta.current_page;
            this.nextPageUrl = response.data.links.next;
          });
      },
      deleteUser(id) {
        this.axios.delete(`${this.BaseUrl}/users/${id}`).then((response) => {
          this.loadData()
        });
      }
    }
};
</script>