<template>
  <div>
    <area-chart :data="data" :download="true" v-if="type=='area'"></area-chart>
    
    <line-chart :data="data" :download="true" v-if="type=='line'"></line-chart>

    <bar-chart :data="data" :download="true" v-if="type=='bar'"></bar-chart>

    <column-chart :data="data" :download="true" v-if="type=='column'"></column-chart>

    <pie-chart :data="data" :download="true" v-if="type=='pie'"></pie-chart>

    <geo-chart :data="data" :download="true" v-if="type=='geo'"></geo-chart>
  </div>
</template>

<script>
export default {
  props: ["type", "data"]
};
</script>