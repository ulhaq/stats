<template>
  <div class="card">
    <div class="card-header"> 
      <select class="inline-select" v-model="graphType">
        <option value="area" selected>Area Graph</option>
        <option value="line" selected>Line Graph</option>
        <option value="bar">Bar Graph</option>
        <option value="column">Column Graph</option>
        <option value="pie">Pie Graph</option>
        <option value="geo" :disabled="section != 'allUserOrigins'">Geo Graph</option>
      </select>
      <div class="float-right">
        <button type="button" class="btn btn-primary" @click="changeSection('allData')">ِAll Data</button>
        <button type="button" class="btn btn-primary" @click="changeSection('allActivities')">All Activities</button>
        <div class="btn-group">
          <button type="button" class="btn btn-primary" @click="changeSection('allUsers')">All Users</button>
          <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu dropdown-menu-right">
            <button class="dropdown-item" @click="changeSection('allUserOrigins')">User Origins</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body text-center table-responsive">
      <loading v-if="!ready" />

      <div v-if="ready">
        <div v-if="section=='allData'">
          <table class="table light-bg text-center">
            <tr>
              <td>
                <input type="datetime-local" title="Start Time" class="form-control inline-block" v-model="start_time" @change="getAllData">
              </td>
              <td>
                <input type="datetime-local" title="End Time" class="form-control inline-block" v-model="end_time" @change="getAllData">
              </td>
            </tr>
          </table>
          <table class="table light-bg text-center">
            <tr>
              <td>
                <select class="form-control" v-model="target" required @change="getAllData">
                  <option value="session">Sessions</option>
                  <option value="action">Actions</option>
                  <option value="variable">Variables</option>
                </select>
              </td>
              <td>
                <select class="form-control" v-model="occurrence" required @change="getAllData">
                  <option value="%D %M %Y">Daily</option>
                  <option value="Week %v of %Y">Weekly</option>
                  <option value="%M of %Y">Monthly</option>
                  <option value="%Y">Annually</option>
                </select>
              </td>
            </tr>
          </table>
        </div>

        <div v-if="section=='allActivities'">
          <table class="table light-bg text-center">
            <tr>
              <td>
                <input type="datetime-local" title="Start Time" class="form-control inline-block" v-model="start_time" @change="getAllActivities">
              </td>
              <td>
                <input type="datetime-local" title="End Time" class="form-control inline-block" v-model="end_time" @change="getAllActivities">
              </td>
            </tr>
          </table>

          <table class="table light-bg text-center">
            <tr>
              <td>
                <select class="form-control" v-model="content.location" required @change="getActions">
                  <option value="" disabled>Select a Location</option>
                  <option :value="optLoc" v-for="optLoc in options.locations" :key="optLoc">{{optLoc}}</option>
                </select>
              </td>
              <td>
                <select class="form-control" v-model="content.action" required @change="getAllActivities" :disabled="content.location==''">
                  <option value="" selected>Select an action</option>
                  <option :value="optAct" v-for="optAct in options.actions" :key="optAct">{{optAct}}</option>
                </select>
              </td>
              <td v-if="content.action != ''">
                <select class="form-control" v-model="occurrence" required @change="getAllActivities">
                  <option value="%D %M %Y">Daily</option>
                  <option value="Week %v of %Y">Weekly</option>
                  <option value="%M of %Y">Monthly</option>
                  <option value="%Y">Annually</option>
                </select>
              </td>
            </tr>
          </table>
        </div>

        <div v-if="section=='allUsers'">
          <table class="table light-bg text-center">
            <tr>
              <td>
                <input type="datetime-local" title="Start Time" class="form-control inline-block" v-model="start_time" @change="getAllUsers">
              </td>
              <td>
                <input type="datetime-local" title="End Time" class="form-control inline-block" v-model="end_time" @change="getAllUsers">
              </td>
            </tr>
          </table>
        </div>

        <div v-if="section=='allUserOrigins'">
          <table class="table light-bg text-center">
            <tr>
              <td>
                <input type="datetime-local" title="Start Time" class="form-control inline-block" v-model="start_time" @change="getAllUserOrigins">
              </td>
              <td>
                <input type="datetime-local" title="End Time" class="form-control inline-block" v-model="end_time" @change="getAllUserOrigins">
              </td>
            </tr>
          </table>
        </div>

        <table class="table" v-if="ready && Object.keys(data).length !== 0">
          <tr>
            <td>
              <charts :type="graphType" :data="data" />
            </td>
          </tr>
        </table>
      </div>

      <table class="table text-center" v-if="ready && Object.keys(data).length === 0">
        <tr>
          <td>We didn't find anything - just empty space.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: {},
      target: "session",
      occurrence: "%D %M %Y",
      start_time: null,
      end_time: null,
      options: {
        locations: [],
        actions: [],
      },
      content: {
        location: "",
        action: "",
      },
      section: 'allData',
      graphType: 'area',
      ready: false
    };
  },
  created() {
      this.start_time = this.moment().subtract(1, "year").format("YYYY-MM-DD\THH:mm");
      this.end_time = this.moment().format("YYYY-MM-DD\THH:mm");

      this.getAllData();
      this.getLocations();
  },
  methods: {
    getAllData() {
      this.axios.get(`${this.BaseUrl}/stats/graphs?target=${this.target}&occurrence=${this.occurrence}&from=${this.start_time}&to=${this.end_time}`).then(response => {
          this.data = response.data;
          this.ready = true;
        });
    },
    getAllActivities() {
      this.axios.get(`${this.BaseUrl}/stats/graphs/actions/${this.content.location}/${this.content.action}?occurrence=${this.occurrence}&from=${this.start_time}&to=${this.end_time}`).then(response => {
          this.data = response.data;
          this.ready = true;
        });
    },
    getAllUsers() {
      this.axios.get(`${this.BaseUrl}/stats/graphs/users?from=${this.start_time}&to=${this.end_time}`).then(response => {
          this.data = response.data;
          this.ready = true;
        });
    },
    getAllUserOrigins() {
      this.axios.get(`${this.BaseUrl}/stats/graphs/users/origins?from=${this.start_time}&to=${this.end_time}`).then(response => {
          this.data = response.data;
          this.ready = true;
        });
    },
    getLocations() {
      this.axios.get(`${this.BaseUrl}/stats/counts`).then(response => {
        this.options.locations = response.data;
      });
    },
    getActions() {
      this.content.action = '';

      this.axios.get(`${this.BaseUrl}/stats/counts?location=${this.content.location}&from=${this.start_time}&to=${this.end_time}`)
      .then(response => {
          this.options.actions = response.data;
      });
    },
    changeSection(section) {
      this.data = {};
      this.section = section;
    }
  }
};
</script>