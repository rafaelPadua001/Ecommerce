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
                    <CouponList :coupons="this.coupons" @edit-coupon="edit" @remove-coupon="remove" />
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
    <div>
        <CreateCoupon v-model="createDialog" :createDialog="createDialog" @create="create"
            @close-dialog="closeDialog" />

        <UpdateCoupon v-model="editDialog" :editCoupon="editCoupon" @update-coupon="updateCoupon"
            @close-dialog="closeUpdateDialog" />

        <v-dialog v-model="removeDialog" width="500">
            <RemoveCoupon :removeCoupon="removeCoupon" @remove="deleteCoupon" @close-dialog="closeRemoveDialog">
            </RemoveCoupon>
        </v-dialog>
    </div>
</template>

<script>
import Dashboard from '../Auth/Dashboard.vue';
import CouponList from './partials/CouponList.vue';
import CreateCoupon from './Dialogs/CreateCoupon.vue';
import UpdateCoupon from './Dialogs/UpdateCoupon.vue';
import RemoveCoupon from './Dialogs/RemoveCoupon.vue';

export default {
    components: {
        Dashboard,
        CouponList,
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
        closeUpdateDialog() {
            this.editDialog = false;
        },
        closeRemoveDialog() {
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
        create(response) {
            this.closeDialog();
            return this.coupons.push(response.data);
        },
        edit(coupon) {

            this.editCoupon = Object.assign({}, coupon);
            return this.editDialog = true;
        },
        updateCoupon(response) {
            const index = this.coupons.findIndex(coupon => coupon.id === response.data.id);
            if (index !== -1) {
                this.coupons.splice(index, 1, response.data);
            }
            // return Object.assign(this.coupons.id[response.data.id], response.data);
        },
        remove(coupon) {
            this.removeCoupon = Object.assign({}, coupon);

            this.removeDialog = true;
        },
        deleteCoupon(removeCoupon) {
            this.coupons.splice(removeCoupon.id, 1)
        }

    },
    mounted() {
        this.getCoupons();
    }
} 
</script>