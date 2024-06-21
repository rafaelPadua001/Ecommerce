<template>
    <v-dialog>
        <v-card class="mx-auto">
            <v-card-title>
                <v-toolbar color="transparent">
                    Remove Address 
                   <template v-slot:append>
                    <v-icon icon="fas fa-close" @click="close">

                    </v-icon>
                   </template>
                   
                </v-toolbar>
            </v-card-title>
            <v-card-text>
                How your shure to remove this address ? 
              
            </v-card-text>
            
            <v-card-actions>
                <v-btn-group>
                    <v-btn color="error" class="mb-4" @click="remove(address)" variant="plain">
                        Remove 
                    </v-btn>
                    <v-btn @click="close" class="mb-4" variant="plain">
                        Close 
                    </v-btn>
                </v-btn-group>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script>
import axios from 'axios';

export default {
    props: ['address'],
    data: () => ({}),
    watch: {
        close(val){
            val || this.close();
        }
    },
    methods: {
        close(){
            this.$emit('close-dialog');
        },
        remove(item){
            axios.delete(`/address/delete/${item.id}`)
            .then((response) => {
                this.$emit('close-dialog');
               
                return this.deleteAddress(item);
                //return this.address = '';
            })
            .catch((response) => {
                alert('Error:' + response);
            });
        },
        deleteAddress(item){
            
            return this.$emit('removeAddress', item)
        }
    }
}
</script>