<template>
    <v-container>
        <v-sheet class="py-2 px-2">
            <v-row fluid>
                <v-col>
                    <Dashboard></Dashboard>
                </v-col>
            </v-row>

            <v-row no-gutters>
                
                <v-col class="d-flex justify-center d-flex">
                   <v-data-table :headers="headers" :items="shippments"
                        :sort-by="[{ key: 'first_name', order: 'asc' }]">
                        <template v-slot:top>
                            <v-toolbar flat class="bg-transparent" >
                                <v-toolbar-title>
                                    Shippments
                                </v-toolbar-title>
                                <v-divider class="mx-2" inset vertical></v-divider>
                                <v-spacer></v-spacer>
                                <v-toolbar-text>
                                    <v-btn class="mr-2" icon size="x-small" variant="plain">
                                    <v-icon class="me-2" color="primary" @click="MelhorEnvioCart(item)"
                                        icon="fas fa-cart-shopping"></v-icon>
                                    </v-btn>
                                </v-toolbar-text>
                            </v-toolbar>

                            
                        </template>
                        
                        <template v-slot:item.actions="{ item }">
                            <v-btn-group>
                                <!-- <v-btn class="mr-2" icon size="x-small" variant="plain">
                                    <v-icon class="me-2" color="primary" @click="MelhorEnvioCart(item)"
                                        icon="fas fa-eye"></v-icon>

                                </v-btn> -->
                                <v-btn>
                                    <v-icon size="x-small" class="me-2" color="primary" @click="checkout(item)"
                                        icon="fas fa-basket-shopping"></v-icon>

                                </v-btn>
                                <v-btn>
                                    <v-icon size="x-small" class="me-2" @click="generatePrint(item)"
                                        icon="fas fa-ticket" color="primary"></v-icon>
                                </v-btn>
                                <v-btn>
                                    <v-icon size="x-small" class="me-2" @click="getPrint(item)" icon="fas fa-print"
                                        color="primary"></v-icon>
                                </v-btn>
                                <v-btn>
                                    <v-icon size="x-small" class="me-2" color="primary"
                                        @click="openTrackingDialog(item)" icon="fas fa-truck-fast"></v-icon>

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
                </v-col>
            </v-row>
            <v-col>
                    <v-dialog 
                        v-model="errorAlert"
                        :text="'response'"
                    >
                        <v-card>
                            {{ this.response }}
                        </v-card>
                      
                    </v-dialog>
                </v-col>

            <v-dialog v-model="updateDialog" max-width="500px">
                <updateDialog :editedItem="this.editedItem" :editedIndex="this.editedIndex" @update-shippment="save"
                    @close-update-dialog="close" />


            </v-dialog>
            <v-dialog v-model="trackingDialog" transition="dialog-bottom-transition">
                <v-card>
                    {{ trackingOrder }}
                    <div>
                        <v-list>
                            <v-list-item-group v-if="trackingOrder">
                                <v-list-item v-for="(value, key) in trackingOrder" :key="key">
                                    <v-list-item-content>
                                        <v-list-item-title>{{ key }}</v-list-item-title>
                                        <v-list-item-subtitle v-if="(typeof value === 'object')">
                                            ID: {{ value.id }}

                                        </v-list-item-subtitle>
                                        <v-list-item-subtitle v-else>
                                            {{ value.id }}
                                        </v-list-item-subtitle>

                                        <v-list-item>
                                            protocol: {{ value.protocol }}
                                        </v-list-item>
                                        <v-list-item> status: {{ value.status }}</v-list-item>
                                        <v-list-item>tracking: <v-btn variant="plain" color="primary" size="x-small"
                                                @click="trackBack(value.tracking)">{{ value.tracking
                                                }}</v-btn></v-list-item>
                                        <v-list-item> created at: {{ value.created_at }}</v-list-item>
                                        <v-list-item>posted at: {{ value.posted_at }}</v-list-item>
                                        <v-list-item> delivered at: {{ value.delivered_at }}</v-list-item>
                                        <v-list-item> canceled at: {{ value.canceled_at }}</v-list-item>
                                        <v-list-item>expired at: {{ value.expired_at }} </v-list-item>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>

                            <v-list-item-group v-if="trackingOrder == 0">
                                <v-list-item-title>Nenhum registro encontrado</v-list-item-title>
                            </v-list-item-group>
                        </v-list>
                    </div>
                    <v-card-actions>
                        <v-btn color="primary" variant="plain" @click="closeTrackingDialog">Close</v-btn>
                    </v-card-actions>

                </v-card>

            </v-dialog>
            <v-dialog v-model="deleteDialog" max-width="500px">
                <DeleteDialog 
                    :editedItem="this.editedItem"
                    @delete-item-confirm="deleteItemConfirm"
                    @close-delete-dialog="closeDeleteDialog"
                />
                <!-- <v-card>
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

                </v-card> -->


            </v-dialog>
        </v-sheet>
    </v-container>

</template>

<script>
import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';
import UpdateDialog from './partials/UpdateDialog.vue';
import DeleteDialog from './partials/DeleteDialog.vue';

export default {
    components: {
        Dashboard,
        UpdateDialog,
        DeleteDialog,
    },
    data: () => ({
        shippments: [],
        trackingOrder: null,
        trackingDialog: false,
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
            // {
            //     title: 'delivery_id',
            //     key: 'delivery_id'
            // },
            {
                title: 'actions',
                key: 'actions',
                sortable: false,
            }
        ],
        updateDialog: false,
        deleteDialog: false,
        editedIndex: -1,
        editedItem: [],
        defaultItem: [],
        errorAlert: false,
        response: false,
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
            console.log(this.shippments[this.editedIndex]);
            this.updateDialog = true;
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
        checkout(item) {
            const data = { order: item.delivery_id };
            axios.post('api/melhorenvio/checkout', data)
                .then((response) => {
                    if (response.data.error) {
                      //  alert(response.data.error);
                        this.errorAlert = true;
                        this.response = response.data.error;
                    }
                   
                    console.log(response.data);
                })
                .catch((response) => {
                   // console.log(response.error);
                    this.errorAlert = true;
                    return alert('Error :', response.error);
                });
        },
        generatePrint(item) {
            const data = { order: item };
            axios.post('/api/generateTicket', data)
                .then((response) => {
                    //window.location.href = response.data.url;
                    console.log(response);
                })
                .catch((response) => {
                    alert('Error:', response.data);
                });
        },
        getPrint(item) {
            const data = { order: item };
            axios.post('/api/getTicket', data)
                .then((response) => {
                    window.location.href = response.data.url;
                    console.log(response);
                })
                .catch((response) => {
                    alert('Error:', response.data);
                });
        },
        openTrackingDialog(item) {
            this.trackingDialog = true;
            return this.tracking(item);
        },
        tracking(item) {
            const data = { order: item };
            axios.post('/api/melhorenvio/tracking', data)
                .then((response) => {
                    // console.log(JSON.parse(response.data));
                    return this.trackingOrder = response.data;
                })
                .catch((response) => {
                    alert('Error: ', response);
                });
        },
        trackBack(trackCode) {
            window.location.href = 'https://app.melhorrastreio.com.br/app/melhorenvio/' + trackCode;
        },
        closeTrackingDialog() {
            this.trackingDialog = false;
        },
        save() {
            const shippmentIndex = this.editedIndex;
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
                    return Object.assign(this.shippments[shippmentIndex], response.data);
                })
                .catch((response) => {

                    return alert('Erro :' + response);
                });
            this.close();
        }
    },
    mounted() {
        this.getShippments();
        this.initialize();
    }
}
</script>