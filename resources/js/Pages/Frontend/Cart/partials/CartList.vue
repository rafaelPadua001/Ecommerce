<template>
    <v-container>
        <v-sheet class="px-2 py-2">
            <v-list :items="carts" item-props lines="three" style="margin-top: 10px; margin-right: -55px">
                <v-list-item v-for="item in carts" :key="item.id" :value="item.id">
                    <div v-if="item && item.is_active == 1">
                        <v-row>
                            <v-col cols="auto" md="12" sm="4">
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
                                            <v-col cols="auto" md="2" sm="4">
                                                <div v-for="(image, index) in JSON.parse(item.images)" :key="index">
                                                    <v-avatar rounded="0" v-if="index === 0">
                                                        <v-img :vid-id="image" class="align-end text-white"
                                                            aspect-ratio="1" :src="`./storage/products/${image}`"
                                                            :lazy-src="`./storage/products/${image}`">
                                                        </v-img>

                                                    </v-avatar>
                                                </div>


                                            </v-col>
                                            <v-col cols="6" md="4" sm="2">
                                                <div>
                                                    <span>{{ item.name }}</span>
                                                </div>
                                            </v-col>


                                        </v-row>
                                        <v-row>
                                            <v-col cols="auto" md="2" sm="1">
                                                <v-card v-bind="props" :color="item.color" :width="30">
                                                    <template v-slot:append>

                                                    </template>
                                                </v-card>

                                            </v-col>
                                            <v-col cols="auto" md="5" sm="2">
                                                <span>Price: {{ item.price }}</span>
                                            </v-col>
                                            <v-col cols="auto" md="5" sm="2">
                                                Quantity: {{ item.quantity }}
                                            </v-col>
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

                <v-btn :color="this.appBarColor ?? 'trasparent'" variant="tonal" block>Checkout</v-btn>

            </v-list>
        </v-sheet>
    </v-container>
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