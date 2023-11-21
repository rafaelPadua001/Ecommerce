<template>
    <div>
        <Dashboard></Dashboard>
    </div>

    <div>
        <v-row fluid>
            <v-col cols="12" sm="12">
                <v-card class="mx-auto" width="500">
                    <v-toolbar title="Coupons" prominent>
                        <template v-slot:append>
                            <v-btn-group>
                                <v-btn @click="openCreateDialog" variant="plain" color="primary">Create New</v-btn>
                            </v-btn-group>
                        </template>
                       
                    </v-toolbar>
                   
                    <v-card-text>

                        <v-list density="compact">
                            <v-list-item v-for="coupon in coupons" :key="coupon.id" color="primary" variant="plain">
                                <template v-slot:append>
                                    <v-menu>
                                        <template v-slot:activator="{ props }">
                                            <v-icon icon="fas fa-ellipsis-vertical" v-bind="props"></v-icon>
                                        </template>
                                        <v-list>
                                            <v-list-item>
                                                
                                                <v-list-item-title>
                                                    
                                                        <v-btn @click="edit(coupon)" variant="plain">
                                                            Edit
                                                        </v-btn>
                                                        <v-btn @click="remove(coupon)" variant="plain">
                                                            Remove
                                                        </v-btn>
                                                    
                                                </v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </template>

                                <v-list-item-title>{{ coupon.code }}</v-list-item-title>


                            </v-list-item>
                        </v-list>
                    </v-card-text>


                </v-card>
            </v-col>
        </v-row>

    </div>


    <div>
        <v-dialog v-model="createDialog" width="500">
            <CreateCoupon :createDialog="createDialog" @create="create"  @close-dialog="closeDialog" />
        </v-dialog>

        <v-dialog v-model="editDialog">
            <UpdateCoupon :editCoupon="editCoupon" @update-coupon="updateCoupon" @close-dialog="closeUpdateDialog"></UpdateCoupon>
        </v-dialog>
        <v-dialog v-model="removeDialog" width="500">
            <RemoveCoupon :removeCoupon="removeCoupon" @remove="deleteCoupon" @close-dialog="closeRemoveDialog"></RemoveCoupon>
        </v-dialog>
    </div>
</template>

<script>
import Dashboard from '../Auth/Dashboard.vue';
import CreateCoupon from './Dialogs/CreateCoupon.vue';
import UpdateCoupon from './Dialogs/UpdateCoupon.vue';
import RemoveCoupon from './Dialogs/RemoveCoupon.vue';

export default {
    components: {
        Dashboard,
        CreateCoupon,
        UpdateCoupon,
        RemoveCoupon
    },
    data: () => ({
        coupons: [],
        createDialog: false,
        menu: false,
        editCoupon: {},
        editDialog: false,
        removeCoupon: {},
        removeDialog: false,
    }),
    methods: {
        openCreateDialog() {
            this.createDialog = true;
        },
        closeDialog() {
            this.createDialog = false;
        },
        closeUpdateDialog(){
            this.editDialog = false;
        },
        closeRemoveDialog(){
            this.removeDialog = false;
        },
        getCoupons() {
            axios.get('/coupons/all')
                .then((response) => {
                    return this.coupons = response.data;
                })
                .catch((response) => {
                    alert('Error:', response.error);
                });
        },
        create(response){
            return this.coupons.push(response.data);
        },
        edit(coupon) {
          this.editCoupon = Object.assign({}, coupon);
          this.editDialog = true;
        },
        updateCoupon(response){
            const index = this.coupons.findIndex(coupon => coupon.id === response.data.id);
            if(index !== -1){
                this.coupons.splice(index, 1, response.data);
            }
          // return Object.assign(this.coupons.id[response.data.id], response.data);
        },
        remove(coupon){
            this.removeCoupon = Object.assign({}, coupon);
            
            this.removeDialog = true;
        },
        deleteCoupon(removeCoupon){
            this.coupons.splice(removeCoupon.id, 1)
        }
        
    },
    mounted() {
        this.getCoupons();
    }
} 
</script>