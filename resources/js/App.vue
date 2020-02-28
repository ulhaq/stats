<template>
  <div class="container">
    <div class="d-flex align-items-center py-4 header"><svg class="bi bi-bar-chart" width="3em" height="3em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" d="M6 13H4v3h2v-3zm5-4H9v7h2V9zm5-5h-2v12h2V4zm-2-1a1 1 0 00-1 1v12a1 1 0 001 1h2a1 1 0 001-1V4a1 1 0 00-1-1h-2zM8 9a1 1 0 011-1h2a1 1 0 011 1v7a1 1 0 01-1 1H9a1 1 0 01-1-1V9zm-5 4a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3z" clip-rule="evenodd"></path>
    </svg> Statistics</div>
    <div class="row content">
        <div class="col-2 sidebar">
            <ul class="nav">
                <li class="nav-item"><router-link :to="{name: 'sessions'}" class="nav-link"><svg class="bi bi-calendar" width="1.2em" height="1.2em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M16 2H4a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2zM3 5.857C3 5.384 3.448 5 4 5h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H4c-.552 0-1-.384-1-.857V5.857z" clip-rule="evenodd"></path>
                  <path fill-rule="evenodd" d="M8.5 9a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm-9 3a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm-9 3a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                </svg> <span>Sessions</span></router-link></li>
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
};
</script>
