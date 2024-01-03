
import Dashboard from '@/Pages/Dashboard.vue';
<template>
    <div>
        <Dashboard />
    </div>
    <div>
        <v-container>
            <v-row fluid>
                <v-col class="d-flex justify-center flex-column" cols="12" md="8" sm="4">
                    <v-sheet>
                        <v-card class="mx-auto pa-2" :width="800">
                            <v-card-text>
                                <v-data-table
                                    class="elevation-0"
                                    :items="this.coupons"
                                    :headers="headers"
                                    :sort-by="[{ key: 'coupon_name', order: 'desc' }]"
                                >

                                <template v-slot:item.actions="{item}">
                                    <v-btn-group>
                                        <v-btn class="me-2" icon variant="plain" size="xs">
                                            <v-icon icon="fas fa-trash" @click="openRemoveCouponDialog(item)"></v-icon>
                                        </v-btn>
                                    </v-btn-group>
                                </template>

                                </v-data-table>
                               
                            </v-card-text>
                        </v-card>

                        <div>
                            <RemoveCoupon 
                                v-model="removeCouponDialog"
                                v-if="removeCouponDialog"
                                :coupons="this.removeCouponItem"
                                @remove-coupon="this.removeItem"
                                @close-dialog="this.removeCouponDialog = false"/>
                        </div>
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>
    </div>
  
</template>

<script>
    import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';
import RemoveCoupon from './partials/Remove.vue';
    export default {
        components: {
            Dashboard,
            RemoveCoupon
        },
        data: () => ({
            coupons: [],
            headers:[
            {
                title: 'name',
                align: 'start',
                key: 'coupon_name',
                sortable: false,
            },
            { title: 'value', key: 'discount_percentage' },
            { title: 'expiration', key: 'end_date' },
            { title: 'Actions', key: 'actions', sortable: false },
            ],
            removeCouponDialog: false,
            removeCouponItem: false,
            removeCouponIndex: -1,
        }),
        methods: {
            getCuponsCustomer(){
                axios.get('/couponsCustomer')
                .then((response) => {
                    return this.coupons = response.data;
                })
                .catch((response) => {
                    alert('Error: ' + response);
                });
            },
            openRemoveCouponDialog(item){
                this.removeCouponIndex = this.coupons.indexOf(item);
                this.removeCouponItem = Object.assign({}, item);
                this.removeCouponDialog = true;
            },
            removeItem(item){
                return this.coupons.splice(item, 1);
            }
           
        },
        mounted(){
            this.getCuponsCustomer();
        }
    }
</script>