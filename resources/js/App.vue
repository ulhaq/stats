<template>
  <div class="container">
    <div class="align-items-center py-4 header">
      <svg class="bi bi-bar-chart" width="3em" height="3em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 13H4v3h2v-3zm5-4H9v7h2V9zm5-5h-2v12h2V4zm-2-1a1 1 0 00-1 1v12a1 1 0 001 1h2a1 1 0 001-1V4a1 1 0 00-1-1h-2zM8 9a1 1 0 011-1h2a1 1 0 011 1v7a1 1 0 01-1 1H9a1 1 0 01-1-1V9zm-5 4a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3z" clip-rule="evenodd"></path></svg> Statistics
    </div>
    <div class="row content">
        <div class="col-2 sidebar">
            <ul class="nav">
                <li class="nav-item"><router-link :to="{name: 'sessions'}" class="nav-link"><svg class="bi bi-calendar" width="1.2em" height="1.2em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 2H4a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2zM3 5.857C3 5.384 3.448 5 4 5h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H4c-.552 0-1-.384-1-.857V5.857z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M8.5 9a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm-9 3a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm-9 3a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg> <span>Sessions</span></router-link></li>
                <li class="nav-item"><router-link :to="{name: 'visitors'}" class="nav-link"><svg class="bi bi-people" width="1.2em" height="1.2em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M17 16s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.995-.944v-.002zM9.022 15h7.956a.274.274 0 00.014-.002l.008-.002c-.002-.264-.167-1.03-.76-1.72C15.688 12.629 14.718 12 13 12c-1.717 0-2.687.63-3.24 1.276-.593.69-.759 1.457-.76 1.72a1.05 1.05 0 00.022.004zm7.973.056v-.002zM13 9a2 2 0 100-4 2 2 0 000 4zm3-2a3 3 0 11-6 0 3 3 0 016 0zm-7.064 4.28a5.873 5.873 0 00-1.23-.247A7.334 7.334 0 007 11c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 017 15c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM6.92 12c-1.668.02-2.615.64-3.16 1.276C3.163 13.97 3 14.739 3 15h3c0-1.045.323-2.086.92-3zM3.5 7.5a3 3 0 116 0 3 3 0 01-6 0zm3-2a2 2 0 100 4 2 2 0 000-4z" clip-rule="evenodd"></path></svg> <span>Visitors</span></router-link></li>
                <li class="nav-item"><router-link :to="{name: 'counts'}" class="nav-link"><svg class="bi bi-graph-up" width="1.2em" height="1.2em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M2 2h1v16H2V2zm1 15h15v1H3v-1z"></path><path fill-rule="evenodd" d="M16.39 6.312l-4.349 5.437L9 8.707l-3.646 3.647-.708-.708L9 7.293l2.959 2.958 3.65-4.563.781.624z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M12 5.5a.5.5 0 01.5-.5h4a.5.5 0 01.5.5v4a.5.5 0 01-1 0V6h-3.5a.5.5 0 01-.5-.5z" clip-rule="evenodd"></path></svg> <span>Counts</span></router-link></li>
            </ul>
            <ul class="nav" v-if="loggedIn()">
                <li class="nav-item"><hr></li>
                <li class="nav-item"><router-link :to="{name: 'users'}" class="nav-link"><svg class="bi bi-person" width="1.2em" height="1.2em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M15 16s1 0 1-1-1-4-6-4-6 3-6 4 1 1 1 1h10zm-9.995-.944v-.002zM5.022 15h9.956a.274.274 0 00.014-.002l.008-.002c-.001-.246-.154-.986-.832-1.664C13.516 12.68 12.289 12 10 12c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664a1.05 1.05 0 00.022.004zm9.974.056v-.002zM10 9a2 2 0 100-4 2 2 0 000 4zm3-2a3 3 0 11-6 0 3 3 0 016 0z" clip-rule="evenodd"></path></svg> <span>Users</span></router-link></li>
                <li class="nav-item"><a href="" class="nav-link" @click.prevent="logout"><svg class="bi bi-box-arrow-left" width="1.2em" height="1.2em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6.354 13.354a.5.5 0 000-.708L3.707 10l2.647-2.646a.5.5 0 10-.708-.708l-3 3a.5.5 0 000 .708l3 3a.5.5 0 00.708 0z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M13.5 10a.5.5 0 00-.5-.5H4a.5.5 0 000 1h9a.5.5 0 00.5-.5z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M16 15.5a1.5 1.5 0 001.5-1.5V6A1.5 1.5 0 0016 4.5H9A1.5 1.5 0 007.5 6v1.5a.5.5 0 001 0V6a.5.5 0 01.5-.5h7a.5.5 0 01.5.5v8a.5.5 0 01-.5.5H9a.5.5 0 01-.5-.5v-1.5a.5.5 0 00-1 0V14A1.5 1.5 0 009 15.5h7z" clip-rule="evenodd"></path></svg> <span>Logout</span></a></li>
            </ul>
        </div>
        <div class="col-md-10">
            <router-view></router-view>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "App",
  methods: {
    logout() {
      this.axios.post('/logout').then(response => {
        document.cookie = 'active=; expires=Jan 01 1970 00:00:01 UTC';
        this.$router.push({name: 'login'})
      });
    }
  }
};
</script>
