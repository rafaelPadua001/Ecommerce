<template>
    <div>
        <v-row fluid>
            <v-col>
                <Dashboard></Dashboard>
            </v-col>
        </v-row>
    </div>
    <v-container>
        <v-row no-gutters>
            <v-col class="d-flex justify-center d-flex">
                <v-sheet>
                    <v-data-table :headers="headers" :items="shippments" :sort-by="[{ key: 'first_name', order: 'asc' }]">
                        <template v-slot:item.actions="{ item }">
                            <v-btn-group>
                                <v-btn class="mr-2" icon size="x-small" variant="plain">
                                    <v-icon class="me-2" color="primary" @click="MelhorEnvioCart(item)"
                                        icon="fas fa-eye"></v-icon>

                                </v-btn>
                                <v-btn>
                                        <v-icon size="x-small" class="me-2" color="primary" @click="checkout(item)"
                                            icon="fas fa-basket-shopping"></v-icon>

                                    </v-btn>
                                    <v-btn>
                                        <v-icon size="x-small" class="me-2" @click="generatePrint(item)" icon="fas fa-ticket"
                                            color="primary"></v-icon>
                                    </v-btn>
                                    <v-btn>
                                        <v-icon size="x-small" class="me-2" color="primary" @click="openTrackingDialog(item)"
                                            icon="fas fa-truck-fast"></v-icon>

                                    </v-btn>
                                <v-btn class="mr-2" icon @click="deleteItem(item)" size="x-small" variant="plain">
                                    <v-icon icon="fas fa-trash" size="small" color="primary"></v-icon>
                                </v-btn>
                                <v-btn class="mr-2" icon @click="editItem(item)" size="x-small" variant="plain">
                                    <v-icon class="me-2" icon="fas fa-pen-to-square" color="primary"></v-icon>
                                </v-btn>
                              
                               


                            </v-btn-group>


                        </template>

                        <template v-slot:no-data>
                            <v-btn color="primary" @click="initialize">
                                Reset
                            </v-btn>
                        </template>
                    </v-data-table>


                </v-sheet>
            </v-col>
        </v-row>

        <div>
            <v-dialog v-model="updateDialog" max-width="500px">
                <v-card>
                    <v-card-title>Update Form</v-card-title>

                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field v-model="editedItem.name" label="Shippment name"></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field v-model="editedItem.company" label="Company"></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field v-model="editedItem.price" label="Price" prefix="R$ " v-bind="config">
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field v-model="editedItem.deadline" label="Deadline"></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-responsive width="150" max-width="165" justify="start">

                                        <v-text-field v-model="editedItem.quantity" label="Quantity" :placeholder="1">
                                            <template v-slot:append>
                                                <v-icon class="grey-lighten-4" size="x-small" @click="quantityIncrement">
                                                    fas fa-plus fa-2xs
                                                </v-icon>
                                            </template>
                                            <template v-slot:prepend>
                                                <v-icon class="grey-lighten-4" size="x-small" @click="quantityDecrement">
                                                    fas fa-minus
                                                </v-icon>
                                            </template>
                                        </v-text-field>
                                    </v-responsive>
                                </v-col>

                            </v-row>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn-group>
                            <v-btn @click="save">Save</v-btn>
                            <v-btn @click="close">Cancel</v-btn>
                        </v-btn-group>
                    </v-card-actions>

                </v-card>


            </v-dialog>

            <v-dialog v-model="deleteDialog" max-width="500px">
                <v-card>
                    <v-card-title>delete item</v-card-title>

                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="6" md="4">
                                    Remove item ? {{ this.editedItem.product_id }}
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn-group>
                            <v-btn @click="deleteItemConfirm">Remove</v-btn>
                            <v-btn @click="closeDeleteDialog">Cancel</v-btn>
                        </v-btn-group>
                    </v-card-actions>

                </v-card>


            </v-dialog>
        </div>
    </v-container>
</template>

<script>
import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';

export default {
    components: {
        Dashboard
    },
    data: () => ({
        shippments: [],
        headers: [
            {
                title: 'user',
                align: 'start',
                sortable: true,
                key: 'first_name',
            },
            {
                title: 'last name',
                sortable: true,
                key: 'last_name'
            },
            {
                title: 'products',
                sortable: true,
                key: 'product_name'
            },
            {
                title: 'shippment',
                sortable: true,
                key: 'name'
            },
            {
                title: 'company',
                key: 'company'
            },
            {
                title: 'price',
                key: 'price'
            },
            {
                title: 'deadline',
                key: 'deadline'
            },
            {
                title: 'quantity',
                key: 'quantity'
            },
            {
                title: 'delivery_id',
                key: 'delivery_id'
            },
            {
                title: 'actions',
                key: 'actions',
                sortable: false,
            }
        ],
        updateDialog: false,
        deleteDialog: false,
        editedIndex: -1,
        editedItem: {},
        defaultItem: [],
    }),
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item';
        },
    },
    watch: {
        close(val) {
            val || this.close();
        },
        closeDeleteDialog(val) {
            val || this.deleteDialog();
        }
    },
    methods: {
        initialize() {
            this.shippments = [];
        },
        getShippments() {
            axios.get('/shippments')
                .then((response) => {
                    return this.shippments = response.data;
                })
                .catch((response) => {
                    return alert('Error:' + response);
                })
        },
        editItem(item) {
            this.editedIndex = this.shippments.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.updateDialog = true;
        },
        quantityIncrement() {
            this.editedItem.quantity++;
        },
        quantityDecrement() {
            if (this.editedItem.quantity >= 1) {
                this.editedItem.quantity--;
            }
            else {
                alert('apenas numeros inteiros são aceitos');
            }

        },
        close() {
            this.updateDialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.default);
                this.editedIndex = -1;
            });
        },
        deleteItem(item) {
            this.editedIndex = this.shippments.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.deleteDialog = true;
        },
        deleteItemConfirm() {
            axios.delete(`/shippments/delete/${this.editedItem.id}`)
                .then((response) => {
                    return true;
                })
                .catch((error) => {
                    return alert('Error:' + error);
                });
            this.closeDeleteDialog();
            this.shippments.splice(this.editedIndex, 1);

        },
        closeDeleteDialog() {
            this.deleteDialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.default);
                this.editedIndex = -1;
            });
        },
        MelhorEnvioCart(item) {
           return window.location.href = `https://sandbox.melhorenvio.com.br/carrinho`;
        },
        checkout(item){
                const data = {order: item.delivery_id};
                axios.post('api/melhorenvio/checkout', data)
                .then((response) => {
                   if(response.data.error){
                    alert(response.data.error);
                   }
                    console.log(response.data);
                })
                .catch((response) => {
                    console.log(response.error)
                    return alert('Error :' , response.error); 
                })
            },
        save() {
            const token = document.querySelector('meta[name="csrf-token"]');
            const data = {
                name: this.editedItem.name.toLowerCase(),
                company: this.editedItem.company,
                price: this.editedItem.price,
                deadline: this.editedItem.deadline,
                quantity: this.editedItem.quantity
            };
            axios.post(`/shippments/update/${this.editedItem.id}`, data,
                {
                    headers: {
                        'X-CSRF-TOKEN': token,
                    }
                })
                .then((response) => {
                    return true;

                })
                .catch((response) => {

                    return alert('Erro :' + response);
                });
            this.close();
            Object.assign(this.shippments[this.editedIndex], response.data);
        }
    },
    mounted() {
        this.getShippments();
        this.initialize();
    }

}
</script>