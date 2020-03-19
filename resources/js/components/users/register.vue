<template>
  <div class="card">
    <div class="card-header">Register a new User</div>
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
                <form @submit.prevent="register">
                  <div class="form-group">
                    <input type="text" class="form-control" placeholder="Full Name" v-model="user.name" />
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email address" v-model="user.email" />
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" v-model="user.password" />
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password Confirmation" v-model="user.password_confirmation" />
                  </div>
                  <button type="submit" class="btn btn-primary form-control">Create User</button>
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
      user: {
        name: null,
        email: null,
        password: null,
        password_confirmation: null,
      },
      errors: {}
    };
  },
  methods: {
    register() {
      this.axios.post('/register', this.user).then(response => {
        this.$router.push({name: 'users'})
      }).catch(error => {
        this.errors = error.response.data.errors;
      });
    }
  }
};
</script>