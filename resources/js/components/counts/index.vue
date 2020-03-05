<template>
  <div class="card">
    <div class="card-header">Counts
        <form @submit.prevent="getCalculations" ref="form">
            <div class="row">
                <div class="col">
                    <select class="form-control" name="location" required @change="getActions">
                        <option value="" disabled selected>Select a location</option>
                        <option :value="optLoc" v-for="optLoc in options.locations" :key="optLoc">{{optLoc}}</option>
                    </select>
                </div>
                <div class="col">
                    <select class="form-control" name="action" required :disabled="!options.actions.length" @change="getVariables">
                        <option value="" disabled selected>Select an action</option>
                        <option :value="optAct" v-for="optAct in options.actions" :key="optAct">{{optAct}}</option>
                    </select>
                </div>
                <div class="col" v-if="options.variables.length">
                    <button type="button" class="btn btn-primary form-control" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body text-center table-responsive">
        <table class="table collapse" id="collapseFilter">
            <tr>
                <td>
                    <div class="row">
                        <div class="col" v-for="variable in options.variables" :key="variable">
                            <input type="text" class="form-control" :placeholder="`Enter ${variable}`" v-model="content.variables[variable]" @keyup="getCalculations">
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        
        <loading v-if="!ready" />
        
        <table class="table light-bg" v-if="counts === null && ready">
            <tr>
                <td>Please select some information</td>
            </tr>
        </table>

        <table class="table light-bg" v-if="counts !== null && ready">
            <tr>
                <td><strong>{{content.location}} </strong> has been <strong>{{content.action}}</strong> total <strong>{{counts}}</strong> times</td>
            </tr>
        </table>
    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            options: {
                locations: [],
                actions: [],
                variables: [],
            },
            content: {
                location: "",
                action: "",
                variables: {},
            },
            counts: null,
            ready: false,
        };
    },
    created() {
        this.axios.get(`${this.BaseUrl}/stats/counts`)
          .then(response => {
                this.options.locations = response.data;
                
                this.ready = true;
          });
    },
    methods: {
        clean(obj) {
            for (var propName in obj) { 
                if (obj[propName] === '') {
                delete obj[propName];
                }
            }
        },
        clearContentVariables() {
            this.content.variables = {};
        },
        getActions() {
            this.ready = false;

            this.clearContentVariables();

            this.content.location = this.$refs.form.location.value
            
            this.axios.get(`${this.BaseUrl}/stats/counts?location=${this.content.location}`)
            .then(response => {
                this.options.actions = response.data;
                
                this.ready = true;
            });

            if (this.options.actions.length) {
                this.getVariables();
                this.getCalculations();
            }
        },
        getVariables() {
            this.ready = false;
            
            this.clearContentVariables();

            this.content.action = this.$refs.form.action.value

            this.axios.get(`${this.BaseUrl}/stats/counts?location=${this.content.location}&action=${this.content.action}`)
                .then(response => {
                    this.options.variables = response.data;
                
                    this.ready = true;
                });
            
            this.getCalculations();
        },
        getCalculations() {
            this.ready = false;

            let data = {
                location: this.content.location,
                action: this.content.action,
            }

            this.clean(this.content.variables)

            if (Object.entries(this.content.variables).length !== 0) {
                data.variables = this.content.variables;
            }

            this.axios.post(`${this.BaseUrl}/stats/counts`, data)
                .then(response => {
                    this.counts = response.data.counts;
                
                    this.ready = true;
                });
        }
    }
};
</script>