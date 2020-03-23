<template>
  <div class="card">
    <div class="card-header">Graphs
      <div class="float-right nav" id="nav-tab" role="tablist">
        <a class="btn btn-primary" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="true">ِAll Data</a>
        <a class="btn btn-primary" id="nav-activity-tab" data-toggle="tab" href="#nav-activity" role="tab" aria-controls="nav-activity" aria-selected="false">Activity</a>
      </div>
    </div>
    <div class="card-body text-center table-responsive">
      <loading v-if="!ready" />

      <nav v-if="ready">

      </nav>
      <div class="tab-content" id="nav-tabContent" v-if="ready">
        <div class="tab-pane fade show active" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
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
                  <option value="M">Monthly</option>
                  <option value="Y">Annually</option>
                </select>
              </td>
              <td v-if="occurrence=='M'">
                <input type="number" min="2000" class="form-control" v-model="year" required @change="getAllData" />
              </td>
            </tr>
          </table>

          <table class="table" v-if="ready && Object.keys(data).length !== 0">
            <tr>
              <td>
                <area-chart :data="data"></area-chart>
              </td>
            </tr>
          </table>
        </div>
        <div class="tab-pane fade" id="nav-activity" role="tabpanel" aria-labelledby="nav-activity-tab">
          <table class="table light-bg text-center">
            <tr>
              <td>
                <select class="form-control" v-model="content.location" required @change="getActions">
                  <option value="" disabled>Select a Location</option>
                  <option :value="optLoc" v-for="optLoc in options.locations" :key="optLoc">{{optLoc}}</option>
                </select>
              </td>
              <td>
                <select class="form-control" v-model="content.action" required @change="getActivity" :disabled="content.location==''">
                  <option value="" selected>Select an action</option>
                  <option :value="optAct" v-for="optAct in options.actions" :key="optAct">{{optAct}}</option>
                </select>
              </td>
              <td v-if="content.action != ''">
                <select class="form-control" v-model="occurrence" required @change="getActivity">
                  <option value="M">Monthly</option>
                  <option value="Y">Annually</option>
                </select>
              </td>
              <td v-if="content.action != '' && occurrence=='M'">
                <input type="number" min="2000" class="form-control" v-model="year" required @change="getActivity" />
              </td>
            </tr>
          </table>

          <table class="table" v-if="ready && Object.keys(data).length !== 0">
            <tr>
              <td>
                <area-chart :data="data"></area-chart>
              </td>
            </tr>
          </table>
        </div>
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
      occurrence: "M",
      year: null,
      options: {
        locations: [],
        actions: [],
      },
      content: {
        location: "",
        action: "",
      },
      ready: false
    };
  },
  created() {
      this.year = this.moment().year();
      this.getAllData();
      this.getLocations();
  },
  methods: {
    getAllData() {
      this.axios.get(`${this.BaseUrl}/stats/graphs?target=${this.target}&occurrence=${this.occurrence}&year=${this.year}`).then(response => {
          this.data = response.data;
          this.ready = true;
        });
    },
    getActivity() {
      this.axios.get(`${this.BaseUrl}/stats/graphs/actions/${this.content.location}/${this.content.action}?occurrence=${this.occurrence}&year=${this.year}`).then(response => {
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

      this.axios.get(`${this.BaseUrl}/stats/counts?location=${this.content.location}`)
      .then(response => {
          this.options.actions = response.data;
      });
    },
  }
};
</script>