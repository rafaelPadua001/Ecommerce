<template>
    <v-dialog>
        <v-row fluid>
            <v-col class="d-flex justify-center mb-6 flex-column" cols="12">
                <v-card class="text-center mx-auto" :width="800">
                    <v-card-title>
                        <v-toolbar color="transparent">
                            Edit Information
                            <template v-slot:append>
                                <v-btn icon @click="closeCustomerDialog">
                                    <v-icon icon="fas fa-close"></v-icon>
                                </v-btn>
                            </template>

                        </v-toolbar>
                    </v-card-title>

                    <v-card-text>
                        <v-row>
                            <v-col>
                                <label>First name:</label>
                                <v-text-field v-model="first_name" :label="customer.first_name"></v-text-field>
                            </v-col>
                            <v-col>
                                <label>Last name:</label>
                                <v-text-field v-model="last_name" :label="customer.last_name"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <label>Email:</label>
                                <v-text-field v-model="email" :label="customer.email"></v-text-field>
                            </v-col>
                            <v-col>
                                <label>New password:</label>
                                <v-text-field v-model="password" type="password" label="New Password"></v-text-field>
                            </v-col>
                            <v-col>
                                <label>Confirm password:</label>
                                <v-text-field label="Confirm new password"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-btn class="me-2" variant="plain" color="error" @click="openDeleteAccount(customer)">Delete account</v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn-group>
                            <v-btn @click="customerUpdate">
                                Save
                            </v-btn>
                            <v-btn @click="closeCustomerDialog">
                                Cancel
                            </v-btn>
                        </v-btn-group>
                    </v-card-actions>
                </v-card>

                <div>
                    <DeleteAccount 
                        v-model="deleteAccountDialog"
                        v-if="deleteAccountDialog"
                        :customer="this.customer"
                        @close-dialog="deleteAccountDialog = false"
                    />
                </div>
            </v-col>
        </v-row>


    </v-dialog>
</template>

<script>
import DeleteAccount from './DeleteAccount.vue';

export default {
    props: ['customer'],
    components: {
        DeleteAccount
    },
    data: () => ({
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        confirmPassword: '',
        email: '',
        deleteAccountDialog: false,
    }),
    methods: {
        customerUpdate() {
            const selectCustomer = Object.assign({}, this.customer);
            let data = {
                first_name: this.first_name,
                last_name: this.last_name,
                email: this.email,
                password: this.password,

            };
            axios.post(`/customer/update/${selectCustomer.id}`, data)
                .then((response) => {
                    this.$emit('close-dialog');
                    return this.$emit('update-customer', response);
                })
                .catch((response) => {
                    return alert('Error :' + response);
                });
        },
        closeCustomerDialog(){
            this.$emit('close-dialog');
        },
        openDeleteAccount(){
            return this.deleteAccountDialog = true;
           
        }
    },
    
}
</script>