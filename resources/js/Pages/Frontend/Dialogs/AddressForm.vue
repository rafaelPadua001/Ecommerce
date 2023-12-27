<template>
    <v-dialog>
       
      <v-card class="mx-auto">
        <v-toolbar>
            <v-toolbar-title> {{ customer.first_name }} {{ customer.last_name }}</v-toolbar-title>
          <template v-slot:append>
            <v-btn icon @click="closeAddressDialog">
              <v-icon icon="fas fa-close"></v-icon>
            </v-btn>
          </template>
        </v-toolbar>
        <v-card-text>
            <AddressForm />
          
        </v-card-text>


        <v-card-actions>
          <v-btn-group>
         <!-- <v-btn blocked  :to="`/profileCustomer/${this.customers.id}`" variant="text">Go</v-btn> -->  
          </v-btn-group>
        </v-card-actions>
      </v-card>


    </v-dialog>

</template>

<script>
import AddressForm from '../Profile/Profile.vue';
    export default{
        props: ['customer'],
        components: {
            AddressForm,
        },
        data: () => ({
            addressDialog: false,
        }),
        watch: {
            closeAddressDialog(val){
                return val || this.closeAddressDialog();
            }
        },
        methods: {
            closeAddressDialog(){
                this.$emit('close-dialog');
            }
        }
    }
</script>