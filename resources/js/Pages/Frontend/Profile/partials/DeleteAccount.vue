<template>
    <v-dialog>
        <v-card class="mx-auto">
            <v-card-title>
                <v-toolbar>
                    Delete Account
                    <template v-slot:append>
                        <v-icon icon="fas fa-close"></v-icon>
                    </template>
                </v-toolbar>
            </v-card-title>

            <v-card-text>
                your shure to remove your account ?
            </v-card-text>

            <v-card-actions>
                <v-btn-group>
                    <v-btn class="me-2" variant="plain" color="error" @click="remove(customer)">Remove</v-btn>
                    <v-btn>Close</v-btn>
                </v-btn-group>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script>
import axios from 'axios';

    export default {
        props: ['customer'],
        data: () => ({}),
        methods: {
            remove(item){
                console.log(item);
                axios.delete(`/customer/delete/${item.id}`)
                .then((response) => {
                    this.$emit('close-dialog');
                    window.location.href='/login';
                })
                .catch((response) => {
                    alert('Error:' + response);
                });
            }
        }
    }
</script>