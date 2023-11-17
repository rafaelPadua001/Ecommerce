<template>
    <div>
        <Dashboard />
    </div>
    <div>
        <v-container>
            <v-row>
                <v-col>
                    <v-sheet>
                        <v-card class="mx-auto">
                            <v-card-text>
                              <v-data-table 
                                :headers="headers"
                                :items="orders"
                                :sort-by="[{ key: 'id', order: 'desc' }]"
                                class="elevation-1"
                            >

                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>Orders</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical>

                                    </v-divider>

                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="dialog" max-width="500px">
                                        <template v-slot:activator="{ props }">
                                            <v-btn color="prymary" dark class="mb-2" v-bind="props" disabled>
                                                New Item
                                            </v-btn>

                                        </template>

                                        <v-card>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12" sm="6" md="4">
                                                        <v-text-field></v-text-field>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>

                            <template v-slot:item.status="{ value }">
                                <v-chip :color="getColor(value)">
                                    {{ value }}
                                </v-chip>
                            </template>


                            <template v-slot:item.actions="{ item }">
                                <v-btn-group>
                                    <v-btn>
                                        <v-icon size="x-small" class="me-2" color="primary" @click="MelhorEnvioCart(item)"
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
                                    
                                    <v-btn>
                                        <v-icon size="x-small" class="me-2" @click="deleteItem(item)" icon="fas fa-remove"
                                            color="primary"></v-icon>
                                    </v-btn>
                                    

                                </v-btn-group>

                            </template>

                            <template v-slot:no-data>
                                <v-btn color="primary" @click="initialize">
                                    Reset
                                </v-btn>
                            </template>

                        </v-data-table>
                            </v-card-text>
                        </v-card>

                        <v-dialog v-model="trackingDialog" transition="dialog-bottom-transition">
                            <v-card>
                                <div>
                                    <v-list>
                                        <v-list-item-group>
                                            <v-list-item v-for="(value, key) in trackingOrder" :key="key">
                                                <v-list-item-content>
                                                    <v-list-item-title>{{ key }}</v-list-item-title>
                                                    <v-list-item-subtitle v-if="(typeof value === 'object')">
                                                        ID: {{ value.id }}
                                                        protocol: {{ value.protocol }}
                                                        status: {{ value.status }}
                                                        tracking: <v-btn variant="plain" color="primary" size="x-small" @click="trackBack(value.tracking)">{{ value.tracking }}</v-btn>
                                                        created at: {{ value.created_at }}
                                                        posted at: {{ value.posted_at }}
                                                        delivered at:   {{ value.delivered_at }}
                                                        canceled at:    {{ value.canceled_at }}
                                                        expired at: {{ value.expired_at }} 
                                                    </v-list-item-subtitle>
                                                    <v-list-item-subtitle v-else>
                                                        {{ value.id }}
                                                    </v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>
                                    </v-list>
                                

                                </div>
                          
                                
                            </v-card>
                           
                        </v-dialog>
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>
    </div>
    
   
</template>

<script>
    import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue'
export default {
        components: {
            Dashboard,
        },
        data: () => ({
            orders: [],
            trackingOrder: null,
            trackingDialog: false,
            dialog: false,
            headers: [
            {
                title: 'id',
                align: 'start',
                key: 'id',
                sortable: false,
            },
            { title: 'client', key: 'client' },
            { title: 'description', key: 'description' },
            { title: 'color', key: 'color' },
            { title: 'Exec Date', key: 'exec_date' },
            { title: 'value', key: 'value' },
            { title: 'status', key: 'status' },
            { title: 'created', key: 'created_at' },
            { title: 'updated', key: 'updated_at' },
            { title: 'Actions', key: 'actions', sortable: false },
        ],
        }),
        watch: {
            dialog(val) {
                val || this.close();
            },
            trackingDialog(val){
                val || this.closeTrackingDialog();
            }
        },
        created() {
            this.initialize();
        },
        
        methods: {
            initialize() {
            this.orders = [];
        },
            getOrders(){
                axios.get('/allOrders')
                .then((response) => {
                   
                    return this.orders = response.data;
                    //return this.orders.push(response.data);
                })
                .catch((response) => {
                    return alert('ERROR:', response);
                });
            },
            getColor(status){
                if(status == 'open') return 'red';
                else if(status == 'close') return 'green';
            },
            MelhorEnvioCart(item){
                const order_id = item.order_id
                return window.location.href = `https://sandbox.melhorenvio.com.br/carrinho`;
            },
            checkout(item){
                const data = {order: item};
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
            generatePrint(item){
                
                const data = {order: item};
                axios.post('/api/generateTicket', data)
                .then((response) => {
                    window.location.href = response.data.url;
               //     console.log(response);
                })
                .catch((response) => {
                    alert('Error:', response.data);
                });
            },
            openTrackingDialog(item){
                this.trackingDialog = true;
                return this.tracking(item);
            },
            tracking(item){
                const data = {order: item};
                axios.post('/api/melhorenvio/tracking', data)
                .then((response) => {
                   // console.log(JSON.parse(response.data));
                    return this.trackingOrder = response.data;
                })
                .catch((response) => {
                    alert('Error: ', response);
                });
            },
            trackBack(trackCode){
                window.location.href = 'https://app.melhorrastreio.com.br/app/melhorenvio/' + trackCode;
               
            },
            closeTrackingDialog(){
                this.trackingDialog = false;
            }
          },
        mounted(){
            this.getOrders();
        }
    }
</script>