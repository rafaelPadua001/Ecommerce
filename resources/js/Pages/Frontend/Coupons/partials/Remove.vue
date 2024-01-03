<template>
    <v-dialog>
        <v-card>
            <v-card-title>
                <v-toolbar class="bg-transparent">
                    <v-toolbar-title>
                        Remove Coupon
                    </v-toolbar-title>
                    <template v-slot:append>
                        <v-btn icon variant="plain">
                            <v-icon icon="fas fa-close"></v-icon>
                        </v-btn>
                        
                    </template>
                </v-toolbar>
               
            </v-card-title>
            <v-card-text>
               Remove this coupon <strong>{{ coupons.coupon_name }}</strong> ?
            </v-card-text>

            <v-card-actions>
                <v-btn-group>
                    <v-btn class="mr-2" variant="text">
                        cancel
                    </v-btn>
                    <v-btn class="mr-2" color="error" variant="text" @click="removeCoupon()">
                        Remove
                    </v-btn>
                    
                </v-btn-group>
            </v-card-actions>
        </v-card>
      
    </v-dialog>
   
</template>

<script>
    export default {
        props: ['coupons'],
        methods: {
        removeCoupon(){
               axios.delete(`/couponsCustomer/delete/${this.coupons.id}`)
                .then((response) => {
                    this.$emit('close-dialog');
                   return this.$emit('remove-coupon', this.coupons);
                   // this.coupons.splice(this.editedIndex, 1);
                })
                .catch((response) => {
                    alert('Error: ' + response);
                })
            }
        }
    }
   
</script>