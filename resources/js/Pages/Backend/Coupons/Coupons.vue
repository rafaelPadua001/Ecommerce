<template>
    <v-row>
        <v-col class="d-flex justify-center flex-column">
            <Dashboard></Dashboard>
        </v-col>
    </v-row>
     <v-row no-gutters>
            <v-col class="d-flex justify-center flex-column" cols="auto">
                <v-card class="mx-auto Welevation-0">
                    <v-card-title>
                        <v-toolbar title="Coupons" prominent>
                        <template v-slot:append>
                            <v-btn-group>
                                <v-btn @click="openCreateDialog" variant="plain" color="primary">Create New</v-btn>
                            </v-btn-group>
                        </template>
                       
                    </v-toolbar>
                    </v-card-title>
                   
                   
                    <v-card-text>
                    <v-list lines="three" item-props>
                            <v-list-item v-for="(coupon, index) in coupons" :key="index" color="primary" variant="plain">
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
                                <template v-slot:prepend>
                                    <v-img :src="`./storage/Coupons/${coupon.images.slice(1, -1)}`"
                                         :lazy-src="`./storage/Coupons/${coupon.images.slice(1, -1)}`"
                                         :width="75"></v-img>
                                </template>
                              
                                <template v-slot:title>{{ coupon.code }}</template>
                              
                                <template v-slot:subtitle v-if="coupon.is_displayed == 1">
                                  Displayed: true
                                </template>
                                <template v-slot:subtitle v-if="coupon.is_displayed == 0">
                                  Displayed: false
                                </template>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                 </v-card>
            </v-col>
        </v-row>
     <div>
        <CreateCoupon v-model="createDialog" :createDialog="createDialog" @create="create"  @close-dialog="closeDialog" />
   
            <UpdateCoupon v-model="editDialog" v-if="editDialog" :editCoupon="editCoupon" @update-coupon="updateCoupon" @close-dialog="closeUpdateDialog" />
       
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
            this.closeDialog();
            return this.coupons.push(response.data);
        },
        edit(coupon) {
          this.editCoupon = Object.assign({}, coupon);
          return this.editDialog = true;
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