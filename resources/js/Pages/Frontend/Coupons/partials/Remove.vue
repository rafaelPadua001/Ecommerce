<template>
    <v-dialog>
        <v-card>
            <v-card-title>
                <v-toolbar class="bg-transparent">
                    <v-toolbar-title>
                        Remove Coupon
                    </v-toolbar-title>
                    <template v-slot:append>
                        <v-btn icon variant="plain">
                            <v-icon icon="fas fa-close"></v-icon>
                        </v-btn>
                        
                    </template>
                </v-toolbar>
               
            </v-card-title>
            <v-card-text>
                Aqui será o dialog de remoção de cupon
            </v-card-text>

            <v-card-actions>
                <v-btn-group>
                    <v-btn class="mr-2" variant="text">
                        cancel
                    </v-btn>
                    <v-btn class="mr-2" color="error" variant="text">
                        Remove
                    </v-btn>
                    
                </v-btn-group>
            </v-card-actions>
        </v-card>
      
    </v-dialog>
   
</template>