<template>
  <div class="card">
    <div class="card-header">Session Details <a href="" @click.prevent="deleteEntry('sessions', entry.id)" class="control-action float-right"><svg class="bi bi-trash" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/></svg></a></div>
    <div class="card-body">
      <loading v-if="!ready" />

      <table class="table table-borderless light-bg" v-if="ready">
        <tbody>
          <tr>
            <th>Started</th>
            <td :title="utcToLocal(entry.created_at)">{{utcToLocal(entry.created_at).fromNow()}}</td>
          </tr>
          <tr>
            <th>ID</th>
            <td>{{entry.id}}</td>
          </tr>
          <tr>
            <th>Visitor</th>
            <td>{{entry.visitor}}</td>
          </tr>
          <tr>
            <th>Client</th>
            <td>{{entry.client}}</td>
          </tr>
          <tr>
            <th>Platform</th>
            <td>{{entry.platform}}</td>
          </tr>
          <tr>
            <th>Origin</th>
            <td>{{entry.origin}}</td>
          </tr>
          <tr class="pointer" data-toggle="collapse" data-target="#actionDetails" aria-expanded="false" aria-controls="actionDetails">
            <th>Total Actions</th>
            <td>{{entry.actions.length}}</td>
          </tr>
        </tbody>
      </table>
      <div class="collapse" id="actionDetails">
        <div class="card card-body table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Location</th>
                <th>Action</th>
                <th>Target</th>
                <th>Total Variables</th>
                <th>Happend</th>
                <th></th>
              </tr>
            </thead>
            <tbody v-for="action in entry.actions" :key="action.id">
              <tr class="pointer" data-toggle="collapse" :data-target="`#variableDetails${action.id}`" aria-expanded="false" :aria-controls="`#variableDetails${action.id}`">
                <td><span class="badge badge-secondary">{{action.id}}</span></td>
                <td>{{action.location}}</td>
                <td>{{action.action}}</td>
                <td>{{action.target}}</td>
                <td>{{action.variables.length}}</td>
                <td :title="utcToLocal(action.created_at)">{{utcToLocal(action.created_at).fromNow()}}</td>
                <td><a href="" @click.prevent="deleteEntry('actions', action.id)" class="control-action float-right"><svg class="bi bi-trash" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/></svg></a></td>
              </tr>
              <tr class="collapse" :id="`variableDetails${action.id}`">
                <td colspan="7">
                  <div class="card card-body">
                    <table class="table table-borderless">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Variable</th>
                          <th>Value</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody v-for="variable in action.variables" :key="variable.id">
                        <tr>
                          <td><span class="badge badge-secondary">{{variable.id}}</span></td>
                            <td>{{variable.variable}}</td>
                            <td>{{variable.value}}</td>
                            <td><a href="" @click.prevent="deleteEntry('variables', variable.id)" class="control-action float-right"><svg class="bi bi-trash" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/></svg></a></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </td>
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
              actions: [],
            },
            ready: false,
        };
    },
    created() {
      this.ready = false;

      this.loadData();
    },
    methods: {
      loadData() {
        this.axios.get(`${this.BaseUrl}/sessions/${this.$route.params.id}?include=actions.variables`).then((response) => {
            this.entry = response.data;
            
            this.ready = true;
        }).catch(error => this.$router.push({name: 'sessions'}));
      },
    },
};
</script>