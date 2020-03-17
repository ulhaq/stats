<template>
  <div class="card">
    <div class="card-header">Session Details</div>
    <div class="card-body">
      <loading v-if="!ready" />

      <table class="table table-borderless light-bg" v-if="ready">
        <tbody>
          <tr>
            <th>Started</th>
            <td>{{utcToLocal(entry.created_at).fromNow()}}</td>
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
              </tr>
            </thead>
            <tbody v-for="action in entry.actions" :key="action.id">
              <tr class="pointer" data-toggle="collapse" :data-target="`#variableDetails${action.id}`" aria-expanded="false" :aria-controls="`#variableDetails${action.id}`">
                <td><span class="badge badge-secondary">{{action.id}}</span></td>
                <td>{{action.location}}</td>
                <td>{{action.action}}</td>
                <td>{{action.target}}</td>
                <td>{{action.variables.length}}</td>
                <td>{{utcToLocal(action.created_at).fromNow()}}</td>
              </tr>
              <tr class="collapse" :id="`variableDetails${action.id}`">
                <td colspan="6">
                  <div class="card card-body">
                    <table class="table table-borderless">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Variable</th>
                          <th>Value</th>
                        </tr>
                      </thead>
                      <tbody v-for="variable in action.variables" :key="variable.id">
                        <tr>
                          <td><span class="badge badge-secondary">{{variable.id}}</span></td>
                            <td>{{variable.variable}}</td>
                          <td>{{variable.value}}</td>
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
        this.axios.get(`${this.BaseUrl}/sessions/${this.$route.params.id}?include=actions.variables`).then((response) => {
            this.entry = response.data;
            
            this.ready = true;
        });
    },
};
</script>