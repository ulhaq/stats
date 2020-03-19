<template>
  <div class="card">
    <div class="card-header">Login</div>
    <div class="card-body light-bg table-responsive">
      <table class="table">
        <tr v-if="Object.entries(errors).length !== 0">
          <td>
            <div class="alert alert-danger" role="alert">
              <span v-for="err in errors" :key="err[0]" style="display: block;">{{err[0]}}</span>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="row"> 
              <div class="col-md-6 offset-md-3">
                <form @submit.prevent="login">
                  <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email address" v-model="credentials.email" />
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" v-model="credentials.password" />
                  </div>
                  <button type="submit" class="btn btn-primary form-control">Login</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      credentials: {
        email: null,
        password: null,
      },
      errors: {}
    };
  },
  methods: {
    login() {
      this.axios.get('/airlock/csrf-cookie');

      this.axios.post('/login', this.credentials).then(response => {
        document.cookie = 'active=true; expires=' + this.moment().utc().add(2, 'hours').format('MMM DD YYYY HH:mm:ss');
        this.$router.push({name: 'sessions'})
      }).catch(error => {
        this.errors = error.response.data.errors;
      });
    }
  }
};
</script>