<template>
   
    <v-row>
        <v-col cols="12">
            <v-window v-model="onboarding" show-arrows="hover">
                <v-window-item v-for="discount in discounts" :key="discount.id" >
                    <v-card
                        max-height="120"
                        v-if="discount.is_used == 0 && discount.is_displayed == 1"
                        color="#952175"
                         theme="dark"
                       
                    >
                    
                        <v-card-title class="text-h6">
                            clique no botão <strong>{{discount.code}}</strong> 
                            e receba <strong>{{ discount.discount_percentage * 100 }}%</strong> de desconto 
                            
                        </v-card-title>
                        <v-card-subtitle>
                            valido até {{ discount.end_date }}
                            
                        </v-card-subtitle>
                        <v-btn variant="text" @click="rescueDiscount(discount)" block>
                                {{ discount.code }}
                            </v-btn>
                        
                     
                    </v-card>
                </v-window-item>
            </v-window>
        </v-col>
    </v-row>
</template>

<script>
  export default {
    props: ['discounts'],
    data: () => ({
      length: 3,
      onboarding: 0,
    }),
    methods: {
        rescueDiscount(item){
            alert('teste');
            console.log(item);
        }
    }
  }
</script>