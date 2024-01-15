<template>
    <div>
        <v-row fluid>
            <v-col>
                <Dashboard />
            </v-col>
        </v-row>
        
    </div>
    <v-container>
            <v-row no-gutters>
                <v-col class="d-flex justify-center flex-column">
                    <v-sheet>
                        <v-data-table 
                                :headers="headers"
                                :items="orders"
                                :sort-by="[{ key: 'id', order: 'desc' }]"
                        >
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>Orders</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical>

                                    </v-divider>

                                    <v-spacer></v-spacer>
                                    
                                </v-toolbar>
                            </template>

                            <template v-slot:item.status="{ value }">
                                <v-chip :color="getColor(value)">
                                    {{ value }}
                                </v-chip>
                            </template>


                            <template v-slot:item.actions="{ item }">
                                <v-btn-group>
                                    <v-btn  class="mr-2" icon size="x-small" variant="plain">
                                        <v-icon  color="primary" icon="fas fa-eye" @click="openOrderDialog(item)">
                                        </v-icon>

                                    </v-btn>
                                   <!-- <v-btn>
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

                                    </v-btn> -->
                                    
                                    <v-btn icon size="x-small" variant="plain">
                                        <v-icon  @click="openRemoveOrderDialog(item)" icon="fas fa-remove"
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
                           
                      
                        <div>
                            <OrderDialog 
                                v-model="orderDialog"
                                v-if="orderDialog"
                                :order="this.editOrder"
                                @update-status="updateOrder"
                                @close-dialog="orderDialog = false"
                                scrollable
                            />
                            
                            <v-dialog v-model="trackingDialog" transition="dialog-bottom-transition">
                            <v-card>
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
                                                    <v-list-item>  status: {{ value.status }}</v-list-item>
                                                    <v-list-item>tracking: <v-btn variant="plain" color="primary" size="x-small" @click="trackBack(value.tracking)">{{ value.tracking }}</v-btn></v-list-item>
                                                    <v-list-item> created at: {{ value.created_at }}</v-list-item>
                                                    <v-list-item>posted at: {{ value.posted_at }}</v-list-item>
                                                    <v-list-item> delivered at:   {{ value.delivered_at }}</v-list-item>
                                                    <v-list-item> canceled at:    {{ value.canceled_at }}</v-list-item>
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

                        <OrderRemove v-model="removeOrderDialog" />
                         
                        </div>
                       
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>
    
    
   
</template>

<script>
import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';
import OrderDialog from '../Orders/partials/OrderDialog.vue';
import OrderRemove from '../Orders/partials/OrderRemove.vue';

export default {
        components: {
            Dashboard,
            OrderDialog,
            OrderRemove
        },
        data: () => ({
            orders: [],
            order: {},
            trackingOrder: null,
            trackingDialog: false,
            dialog: false,
            orderDialog: false,
            removeOrderDialog: false,
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
        editOrder: [],
        editIndex: -1,
       
        }),
        watch: {
            dialog(val) {
                val || this.close();
            },
            trackingDialog(val){
                val || this.closeTrackingDialog();
            },
            removeOrderDialog(val){
                val || this.closeRemoveOrderDialog();
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
            openOrderDialog(item){
                this.editOrder = Object.assign({}, item);
                this.editIndex = this.orders.indexOf(item);
               return this.orderDialog = true;
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
            },
            updateOrder(item){
                this.orderDialog = false;
                return this.editOrder = Object.assign(this.orders[this.editIndex], item.data);
            },
            openRemoveOrderDialog(item){
                this.removeOrderDialog = true;
                this.order = Object.assign({}, item);
                //return this.removeOrder(item);
            },
            removeOrder(item){
               // alert(item);
                axios.post(`/api/melhorenvio/delete/${this.order.id}`)
                .then((response) => {
                   // this.orders.splice(item, 1);
                    this.closeOrderDialog();
                    return this.orders.splice(item, 1);
                })
                .catch((response) => {
                    alert('Error :', response);
                });
            },
            closeOrderDialog(){
                return this.openRemoveOrderDialog = false;
            }

          },
        mounted(){
            this.getOrders();
        }
    }
</script>