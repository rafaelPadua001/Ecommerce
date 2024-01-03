
import Dashboard from '@/Pages/Dashboard.vue';
<template>
    <div>
        <Dashboard />
    </div>
    <div>
        <v-container>
            <v-row no-gutters>
                <v-col class="d-flex justify-center flex-column" cols="12" md="8">
                    <v-sheet>
                        <v-card class="mx-auto" :width="800">
                            <v-card-text>
                                <v-data-table
                                    class="elevation-0"
                                    :items="this.coupons"
                                    :headers="headers"
                                    :sort-by="[{ key: 'id', order: 'desc' }]"
                                    
                                >

                                </v-data-table>
                               
                            </v-card-text>
                        </v-card>
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>
    </div>
  
</template>

<script>
    import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';
    export default {
        components: {
            Dashboard
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
            ]
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
            }
        },
        mounted(){
            this.getCuponsCustomer();
        }
    }
</script>