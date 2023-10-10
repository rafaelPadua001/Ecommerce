
<template>
    <div>
        <Dashboard />
    </div>

    <div>
        <v-card
    class="mx-auto"
    max-width="434"
    rounded="0"
  >
    <v-img
      height="100%"
      cover
      src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg"
    >
      <v-avatar
        color="grey"
        size="150"
        rounded="0"
      >
        <v-img cover src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"></v-img>
      </v-avatar>
      <v-list-item
        class="text-white"
        :title="customer.first_name"
        :subtitle="customer.last_name"
      ></v-list-item>

      <v-list-item
        class="text-white"
        :title="customer.email"
       
      ></v-list-item>
    </v-img>
    
  </v-card>
       
    </div>
  
</template>

<script>
    import Dashboard from '../Auth/Dashboard.vue';

    export default{
        components: {
            Dashboard
        },
        data: () => ({
            customer: false,
        }),
        methods: {
            getCustomer(){
                axios.get('/customer')
                .then((response) => {
                    console.log(response.data);
                    return this.customer = response.data; 
                })
                .catch((response) => {
                    return alert('Error :' + response); 
                })
            }
        },
        created(){
            this.getCustomer();
        }
    }
</script>