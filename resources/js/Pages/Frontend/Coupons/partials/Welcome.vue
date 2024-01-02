<template>
    <v-dialog>
        <v-card>
            <v-card-title>
             <v-toolbar>
                    Welcome Discount
                    <template v-slot:append>
                        <v-icon icon="fas fa-close"></v-icon>
                    </template>
                </v-toolbar>
            </v-card-title>

            <v-card-text>
               {{coupon.code}} {{coupon.discount_percentage}}  
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        props:['coupon'],
    }
</script>