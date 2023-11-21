<template>
    <v-card class="mx-auto" width="500">
        <v-toolbar title="Coupons Generator">
            <v-btn icon @click="close">
                <v-icon icon="fas fa-close fa-2xs"></v-icon>
            </v-btn>
        </v-toolbar>

        

        <v-card-text>
            Are you're shure to remove this coupon ? <strong>{{ removeCoupon.code }}</strong>
        </v-card-text>

        <v-card-actions>
            <v-btn-group>
                <v-btn variant="plain" class="mb-2" @click="remove">Remove</v-btn>
                <v-btn variant="plain" class="mb-2" @click="close">Close</v-btn>
            </v-btn-group>
        </v-card-actions>
      </v-card>
</template>

<script>
import axios from 'axios';

    export default{
        props: ['removeCoupon'],
        methods: {
            close(){
                this.$emit('close-dialog');
            },
            remove(removeCoupon){
                axios.delete(`/coupons/delete/${this.removeCoupon.id}`)
                .then((response) => {
                    this.$emit('close-dialog');
                    return this.$emit('remove', removeCoupon);
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                })
            }
        }
    }
</script>