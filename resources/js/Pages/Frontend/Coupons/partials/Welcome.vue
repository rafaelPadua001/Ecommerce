<template>
    <v-dialog width="900" scrollable>
       <v-row no-gutters justify-center>
                    <v-col cols="6">
                        <v-card
                            class="bg-green-accent-3 text-white"
                            elevation="0"
                        >
                            <v-card-title>
                                Save Now!
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-spacer></v-spacer>

                            <v-card-text>
                                <p class="text-h6">
                                    Receive a {{ coupon.discount_percentage * 100 }} % coupon discount
                                </p>
                                <div class="text-caption pa-6 font-weight-small">
                                    <p>
                                    Sign up and get a special discount coupon! Join us for exclusive access to amazing deals
                                    and
                                    unmissable updates. Click "Join Us" and start saving today!
                                    </p>
                                </div>
                               
                                <p>
                                    <v-btn class="mr-2" block rounded="xl" variant="text" :to="`/register`">
                                        Join Us
                                    </v-btn>
                                </p>
                               
                                <div class="text-caption pa-4">
                                    <div class="text-white">*Add the desired items to your shopping cart. </div>
                                   <div class="font-weight-medium">
                                        When you're ready to complete your purchase, head to the payment section. Locate
                                        the "Discount Coupon" or "Promo Code" field. Your coupon will already be linked to
                                        your profile, so there's no need to enter the code. Just ensure that the discount
                                        has been automatically applied. 
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    
                    <v-col class="d-flex justify-center text-white flex-column" cols="6">
                            <v-img
                                src="https://picsum.photos/1920/1080?random"
                                cover
                                aspect-ratio="1/1"
                            >
                                <v-toolbar color="transparent">
                                    <template v-slot:append>
                                        <v-btn class="mr-2" icon @click="closeWelcomeDialog">
                                            <v-icon icon="fas fa-close"></v-icon>
                                        </v-btn>
                                        
                                    </template>
                                </v-toolbar>
                            </v-img>
                            
                       
                    </v-col>
                </v-row>
     </v-dialog>
</template>

<script>
export default {
    props: ['coupon'],
    methods: {
        closeWelcomeDialog(){
            this.$emit('close-welcome-dialog');
        }
    }
}
</script>