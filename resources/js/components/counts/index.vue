<template>
  <div class="card">
    <div class="card-header">Counts</div>
    <div class="card-body text-center table-responsive" >

        <table class="table light-bg">
            <tbody>
                <tr v-if="options.locations.length">
                    <th>Location</th>
                    <td>
                        <select class="form-control" v-model="content.location" required @change="getActions" size="5">
                            <option :value="optLoc" v-for="optLoc in options.locations" :key="optLoc">{{optLoc}}</option>
                        </select>
                    </td>
                </tr>
                <tr v-if="options.actions.length">
                    <th>Action</th>
                    <td>
                        <select class="form-control" v-model="content.action" required @change="getTargets" size="5">
                            <option value="" selected>Select an action</option>
                            <option :value="optAct" v-for="optAct in options.actions" :key="optAct">{{optAct}}</option>
                        </select>
                    </td>
                </tr>
                <tr v-if="options.targets.length">
                    <th>Target</th>
                    <td>
                        <select class="form-control" v-model="content.target" required @change="getVariables" size="5">
                            <option value="" selected>Select a target</option>
                            <option :value="optTar" v-for="optTar in options.targets" :key="optTar">{{optTar}}</option>
                        </select>
                    </td>
                </tr>
                <tr v-if="options.variables.length && content.target != ''">
                    <td colspan="2">
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
            </tbody>
        </table>
        
        <table class="table" v-if="counts !== null && ready">
            <tr v-if="content.action == ''">
                <td><strong>{{counts}}</strong> {{counts > 1 || counts == 0 ? "interactions" : "interaction"}} with <strong>{{content.location}}</strong></td>
            </tr>
            <tr v-if="content.action != ''">
                <td><strong>{{counts}}</strong> {{counts > 1 || counts == 0 ? "times" : "time"}} <strong>{{content.location}}</strong> has been <strong>{{content.action}}</strong></td>
            </tr>
        </table>

        <table class="table light-bg text-center" v-if="ready && !options.locations.length">
            <tr>
                <td>We didn't find anything - just empty space.</td>
            </tr>
        </table>

        <loading v-if="!ready" />
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
                targets: [],
                variables: [],
            },
            content: {
                location: "",
                action: "",
                target: "",
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
            this.content.target = '';
            this.content.variables = {};

            this.axios.get(`${this.BaseUrl}/stats/counts?location=${this.content.location}`)
            .then(response => {
                this.options.actions = response.data;
                
                this.getVariables();
                
                this.ready = true;
            });
        },
        getTargets() {
            this.ready = false;
            
            this.content.target = '';

            if (this.content.action != '') {
                this.axios.get(`${this.BaseUrl}/stats/counts?location=${this.content.location}&action=${this.content.action}`)
                    .then(response => {
                        this.options.targets = response.data;
                    
                        this.ready = true;
                    });
            }

            this.getCalculations();
        },
        getVariables() {
            this.ready = false;

            if (this.content.target != '') {
                this.axios.get(`${this.BaseUrl}/stats/counts?location=${this.content.location}&action=${this.content.action}&target=${this.content.target}`)
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

            if (this.content.target != '') {
                data.target = this.content.target
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