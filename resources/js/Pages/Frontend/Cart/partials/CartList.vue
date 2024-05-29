<template>
    <v-list :items="carts" item-props :width="250" :max-height="625" lines="three"
        style="margin: 0.6rem auto; margin-right: -15%; padding: 0%">
        <v-list-item v-for="item in carts" :key="item.id" :value="item.id">
            <div v-if="item && item.cart_item_status == 1">
                <v-row no-gutters justify="center">
                    <v-col cols="auto">
                        <v-card class="mx-auto" elevation="0">
                            <template v-slot:append>
                                <v-btn-group>
                                    <v-btn v-bind="props" icon size="x-small" variant="plain">
                                        <v-icon icon="fas fa-close fa-2xs" @click="removeItem(item)"></v-icon>
                                    </v-btn>
                                </v-btn-group>
                            </template>

                            <v-card-text>
                                <v-row no-gutters>
                                    <v-col cols="auto">
                                        <div v-for="(image, index) in parsedImages(item.images)" :key="index">
                                            <v-avatar rounded="0" v-if="index === 0">
                                                <v-img :vid-id="image" class="align-end text-white" aspect-ratio="1"
                                                    :src="`./storage/products/${image}`"
                                                    :lazy-src="`./storage/products/${image}`">

                                                </v-img>
                                             </v-avatar>
                                       </div>


                                    </v-col>

                                    <v-col cols="12">
                                        <v-avatar v-for="(color, index) in parsedColors(item.cart_item_colors)" :key="index" v-bind="props" :color="color">
                                            <template v-slot:append>
                                              
                                            </template>
                                        </v-avatar>

                                    </v-col>
                                    <v-col cols="auto" v-for="(size, index) in parsedSizes(item.cart_item_size)" :key="index">
                                        <v-avatar v-bind="props" color="grey" size="40">
                                          
                                                <span>{{ size }}</span>
                                           
                                        </v-avatar>

                                    </v-col>
                                    <v-col cols="12">
                                        <div>
                                            <span><strong>{{ item.name }}</strong></span>
                                        </div>
                                    </v-col>

                                    <v-spacer></v-spacer>
                                    <v-col cols="12">
                                        <span><strong>Price:</strong></span>
                                        R$ {{ item.cart_item_price }} x {{ item.shippment_quantity }}
                                    </v-col>
                                    <v-col cols="12">
                                       <span><strong>Delivery:</strong></span> {{item.shippment_name}}
                                    </v-col>
                                    <v-col cols="12">
                                        
                                        <span><strong>Price:</strong></span>
                                          R$ {{ item.shippment_price }} x {{ item.shippment_quantity }}
                                     </v-col>
                                     <v-col cols="12">
                                       <span><strong>Delivery Total:</strong></span> {{item.delivery_price}}
                                    </v-col>
                             
                                    <v-col cols="8">
                                        <strong>Quantity:</strong> {{ item.shippment_quantity }}
                                    </v-col>
                                    <v-col cols="8">
                                        <span><strong>Total:</strong> R$ {{item.total_price  }}</span>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    
                                   
                                   
                                    <v-col cols="auto" sm="2">
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>

                <v-divider></v-divider>
            </div>

        </v-list-item>
        <span><strong>Total:</strong></span>
        <v-btn :color="this.appBarColor ?? 'trasparent'" variant="tonal" block>Checkout</v-btn>

    </v-list>
</template>

<script>
export default {
    props: {
        carts: {
            type: Array,
            required: true
        }
    },
    data: () => {

    },
    computed: {
    // Propriedade computada que retorna uma função
    parsedImages() {
      return imagesString => JSON.parse(imagesString);
    },
    parsedColors(){
     return colorsString => JSON.parse(colorsString);
    },
    parsedSizes(){
        return sizesString => JSON.parse(sizesString);
    }
  },
    methods: {
        removeItem(item) {
            axios.delete(`/cartItem/delete/${item.id}`)
                .then((response) => {
                    return this.carts.splice(this.carts.indexOf(item), 1);
                })
                .catch((response) => {
                    return alert('Error :' + response);
                });
        },
    }
}
</script>