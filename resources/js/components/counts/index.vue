<template>
  <div class="card">
    <div class="card-header">Counts
        <form @submit.prevent="getCalculations">
            <div class="row">
                <div class="col">
                    <select class="form-control" v-model="content.location" required @change="getActions">
                        <option value="" disabled selected>Select a location</option>
                        <option :value="optLoc" v-for="optLoc in options.locations" :key="optLoc">{{optLoc}}</option>
                    </select>
                </div>
                <div class="col">
                    <select class="form-control" v-model="content.action" required :disabled="!options.actions.length" @change="getVariables">
                        <option value="" selected>Select an action</option>
                        <option :value="optAct" v-for="optAct in options.actions" :key="optAct">{{optAct}}</option>
                    </select>
                </div>
                <div class="col" v-if="options.variables.length && content.action != ''">
                    <button type="button" class="btn btn-primary form-control" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body text-center table-responsive" >
        <table class="table collapse" id="collapseFilter" v-if="options.variables.length && content.action != ''">
            <tr>
                <td>
                    <div class="row">
                        <div class="col" v-for="variable in options.variables" :key="variable">
                            <div class="input-group">
                                <input type="text" class="form-control" :placeholder="`Enter ${variable}`" v-model="content.variables[variable]" @keyup="getCalculations">
                                <div class="input-group-prepend" v-if="options.variables[options.variables.length-1] != variable">
                                    <span class="input-group-text">OR</span>
                                </div>
                            </div>
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

        <table class="table light-bg" v-if="counts !== null && content.action == '' && ready">
            <tr>
                <td><strong>{{counts}}</strong> {{counts > 1 || counts == 0 ? "interactions" : "interaction"}} with <strong>{{content.location}}</strong></td>
            </tr>
        </table>

        <table class="table light-bg" v-if="counts !== null && content.action != '' && ready">
            <tr>
                <td><strong>{{counts}}</strong> {{counts > 1 || counts == 0 ? "times" : "time"}} <strong>{{content.location}}</strong> has been <strong>{{content.action}}</strong></td>
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
        getActions() {
            this.ready = false;

            this.options.variables = [];
            this.content.action = '';
            this.content.variables = {};

            this.axios.get(`${this.BaseUrl}/stats/counts?location=${this.content.location}`)
            .then(response => {
                this.options.actions = response.data;
                
                this.getVariables();
                
                this.ready = true;
            });
        },
        getVariables() {
            this.ready = false;

            if (this.content.action != '') {
                this.axios.get(`${this.BaseUrl}/stats/counts?location=${this.content.location}&action=${this.content.action}`)
                    .then(response => {
                        this.options.variables = response.data;
                    
                        this.ready = true;
                    });
            }

            this.getCalculations();
        },
        getCalculations() {
            this.ready = false;

            let data = {
                location: this.content.location,
            }

            if (this.content.action != '') {
                data.action = this.content.action
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