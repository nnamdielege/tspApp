<template>
    <div>
      <h3>Grocery list</h3>
      <ul>
        <li v-for="(grocery, index) in groceries" :key="index">
          {{ grocery }}
          <button @click="toggleBought(index)">Toggle Bought</button>
          <div v-if="isBought(index)">Bought!</div>
        </li>
      </ul>
    </div>
  </template>
  
<script>
  export default {
    data() {
      return {
        groceries: ['Bread', 'Milk'],
        boughtItems: []
      };
    },
    methods: {
      toggleBought(index) {
        if (this.isBought(index)) {
          this.boughtItems.splice(this.boughtItems.indexOf(index), 1);
        } else {
          this.boughtItems.push(index);
        }
      },
      isBought(index) {
        return this.boughtItems.includes(index);
      }
    }
  };
</script>
  
<style>
  /* Add your styles here */
</style>
  