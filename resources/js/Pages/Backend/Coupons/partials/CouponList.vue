<template>
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
                    :lazy-src="`./storage/Coupons/${coupon.images.slice(1, -1)}`" :width="75"></v-img>
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
</template>

<script>
export default {
    name: 'CouponList',
    props: ['coupons'],
    methods: {
        edit(coupon){
            return this.$emit('edit-coupon', coupon);
        }
    }
}
</script>